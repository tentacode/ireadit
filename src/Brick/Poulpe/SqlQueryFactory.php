<?php

declare(strict_types=1);

namespace App\Brick\Poulpe;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Webmozart\Assert\Assert;

class SqlQueryFactory
{
    private \PDO $pdo;

    public function __construct(ManagerRegistry $doctrineRegistry)
    {
        $doctrineConnection = $doctrineRegistry->getConnection();
        Assert::isInstanceOf($doctrineConnection, Connection::class);
        Assert::isInstanceOf($doctrineConnection->getNativeConnection(), \PDO::class);

        $this->pdo = $doctrineConnection->getNativeConnection();
    }

    public function __invoke(): SqlQuery
    {
        return new SqlQuery($this->pdo);
    }
}
