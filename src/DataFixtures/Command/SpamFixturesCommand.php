<?php

declare(strict_types=1);

namespace App\DataFixtures\Command;

use App\Entity\RegistrationStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

#[AsCommand(name: 'fixtures:spam', description: 'Creating a lot of links',)]
class SpamFixturesCommand extends Command
{
    const BATCH_SIZE = 5000;

    private \PDO $pdo;
    private SymfonyStyle $io;

    public function __construct(EntityManagerInterface $em)
    {
        $connection = $em->getConnection();
        Assert::isInstanceOf($connection->getNativeConnection(), \PDO::class);

        $this->pdo = $connection->getNativeConnection();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('link-number', null, InputOption::VALUE_REQUIRED, 'Number of links', 10)
            ->addOption('user-number', null, InputOption::VALUE_REQUIRED, 'Number of users', 2)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Assert::inArray($_ENV['APP_ENV'], ['dev', 'test', 'local'], 'Cannot spam links in production.');

        $this->io = new SymfonyStyle($input, $output);

        $userNumber = (int) $input->getOption('user-number');
        $linkNumber = (int) $input->getOption('link-number');

        // inserting users
        $this->insertBulk(
            $userNumber,
            'user',
            [
                'uuid', 
                'email', 
                'username', 
                'roles', 
                'registration_status', 
                'registration_date',
            ], 
            function (int $currentIndex) {
                return [
                    Uuid::v4(),
                    sprintf('fake%s@example.com', $currentIndex),
                    sprintf('fake%s', $currentIndex),
                    '["ROLE_USER"]',
                    'validated',
                    '2017-01-02'
                ];
            }
        );

        $statement = $this->pdo->query('SELECT MAX(id) FROM "user";');
        $statement->execute();
        $maxUserId = $statement->fetchColumn(0);

        // inserting links
        $this->insertBulk(
            (int) $linkNumber,
            'link',
            [
                'uuid', 
                'title', 
                'url', 
                'type', 
                'creation_date', 
                'metas',
                'image_url',
            ], 
            function (int $currentIndex) {
                return [
                    Uuid::v4(),
                    'Title ' . $currentIndex,
                    'https://example.com/' . $currentIndex,
                    'Outil',
                    '2017-01-01',
                    '[]',
                    'http://ireadit.test/fixtures/steam.png',
                ];
            }
        );

        $statement = $this->pdo->query('SELECT MAX(id) FROM "link";');
        $statement->execute();
        $maxLinkId = $statement->fetchColumn(0);

        // inserting events
        $this->insertBulk(
            (int) $linkNumber,
            'link_event',
            [
                'link_id', 
                'author_id', 
                'event_date', 
                'type', 
            ], 
            function (int $currentIndex) use ($maxUserId, $maxLinkId) {
                return [
                    mt_rand(1, $maxLinkId),
                    mt_rand(1, $maxUserId),
                    '2017-01-01',
                    'added',
                ];
            }
        );

        return Command::SUCCESS;
    }

    private function insertBulk(int $bulkNumber, string $table, array $fields, callable $valuesFunction)
    {
        $progress = $this->io->createProgressBar($bulkNumber);

        $offset = 0;
        $batch = self::BATCH_SIZE;
        $this->pdo->beginTransaction();
        while ($offset < $bulkNumber) {
            $limit = $offset + $batch;
            if ($limit > $bulkNumber) {
                $limit = $bulkNumber;
            }

            $data = [];

            for ($currentIndex = $offset; $currentIndex < $limit; $currentIndex++) {
                $batchData = call_user_func($valuesFunction, $currentIndex);
                foreach ($batchData as $singleData) {
                    $data[] = $singleData;
                }
            }

            $interogationString = '(' . implode(',', array_fill(0, count($fields), '?')) . ')';
            $interogationString .= str_repeat(', ' . $interogationString, $limit - $offset - 1);

            $sql = sprintf(
                'INSERT INTO "%s" (%s) VALUES ' . $interogationString,
                $table,
                implode(',', $fields)
            );

            $statement = $this->pdo->prepare($sql);
            $statement->execute($data);

            $offset = $limit + 1;
            $progress->advance($batch);
        }

        $this->pdo->commit();
        $this->io->newLine();
        $this->io->newLine();
        $this->io->success(sprintf('Created %d %s', $bulkNumber, $table));
    }
}
