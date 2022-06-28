<?php

declare(strict_types=1);

namespace App\Brick\Link\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

#[AsCommand(name: 'spam:link', description: 'Creating a lot of links',)]
class SpamLinkCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('number', null, InputOption::VALUE_REQUIRED, 'Number of links', 10)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Assert::inArray($_ENV['APP_ENV'], ['dev', 'test', 'local'], 'Cannot spam links in production.');

        $io = new SymfonyStyle($input, $output);
        Assert::integer($input->getOption('number'));
        $number = (int) $input->getOption('number');

        $progress = $io->createProgressBar($number);

        $connection = $this->em->getConnection();
        $pdo = $connection->getNativeConnection();
        Assert::isInstanceOf($pdo, \PDO::class);

        $offset = 0;
        $batch = 10000;
        $pdo->beginTransaction();
        while ($offset < $number) {
            $limit = $offset + $batch;
            if ($limit > $number) {
                $limit = $number;
            }

            $data = [];
            for ($i = $offset; $i < $limit; $i++) {
                $data[] = Uuid::v4();
                $data[] = 'Title ' . $i;
                $data[] = 'https://example.com/' . $i;
                $data[] = 'link';
            }

            $interogationString = '(?, ?, ?, ?)';
            $interogationString .= str_repeat(', (?, ?, ?, ?)', $limit - $offset - 1);

            $sql = 'INSERT INTO link (uuid, title, url, type) VALUES ' . $interogationString;
            $statement = $pdo->prepare($sql);
            $statement->execute($data);

            $offset = $limit + 1;
            $progress->advance($batch);
        }

        $pdo->commit();
        $io->newLine();
        $io->success(sprintf('Created %d links', $number));

        return Command::SUCCESS;
    }
}
