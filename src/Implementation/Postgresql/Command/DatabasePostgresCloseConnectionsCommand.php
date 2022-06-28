<?php

declare(strict_types=1);

namespace App\Implementation\Postgresql\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

#[AsCommand(
    name: 'database:postgres:close-connections',
    description: 'Closes every connection to Postgres database (useful before dropping the database)',
)]
class DatabasePostgresCloseConnectionsCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sql = <<<CODE_SAMPLE
            SELECT pg_terminate_backend(pid) 
            FROM pg_stat_activity WHERE datname = current_database();
            CODE_SAMPLE;

        try {
            $connection = $this->em->getConnection();
            $pdo = $connection->getNativeConnection();
            Assert::isInstanceOf($pdo, \PDO::class);

            $statement = $pdo->prepare($sql);
            $statement->execute();
        } catch (\Throwable $e) {
            // Closing connections throws an exception, this is to be expected.
            if (strpos($e->getMessage(), 'server closed the connection unexpectedly') !== false) {
                $io->success('All connections have been closed.');

                return Command::SUCCESS;
            }

            if (strpos($e->getMessage(), 'database "ireadit" does not exist') !== false) {
                $io->comment('No need to close connection, database does not exist.');

                return Command::SUCCESS;
            }

            throw $e;
        }

        throw new \LogicException('An exception was expected.');
    }
}
