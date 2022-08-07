<?php

declare(strict_types=1);

namespace App\Brick\Poulpe;

class SqlQuery
{
    public function __construct(private \PDO $pdo)
    {
        // $pdo->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
    }

    /**
     * @param array<mixed> $parameters
     */
    public function query(string $sql, array $parameters = []): SqlResult
    {
        $statement = $this->pdo->prepare($sql);

        foreach ($parameters as $key => $value) {
            $statement->bindValue($key, $value);
        }

        $statement->execute();

        $raw = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return new SqlResult($raw);
    }
}
