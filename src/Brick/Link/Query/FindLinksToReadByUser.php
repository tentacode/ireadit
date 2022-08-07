<?php

declare(strict_types=1);

namespace App\Brick\Link\Query;

use App\Brick\Link\Result\UserLink;
use App\Brick\Poulpe\SqlQuery;

class FindLinksToReadByUser
{
    public function __construct(private SqlQuery $sqlQuery)
    {
    }

    /**
     * @return array<UserLink>
     */
    public function __invoke(int $userId): array
    {
        $sql = <<<CODE_SAMPLE
            SELECT
                link.title,
                link.url,
                link.image_url AS "imageUrl",
                link.description
            FROM link
            JOIN link_event added_link
                ON added_link.link_id = link.id
                AND added_link.author_id = :author_id
                AND added_link.type = 'added'
            LEFT JOIN link_event read_link
                ON read_link.link_id = link.id
                AND read_link.author_id = :author_id
                AND read_link.type = 'read'
            WHERE read_link.id IS NULL
            GROUP BY link.id
            LIMIT 100;
        CODE_SAMPLE;

        $result = $this->sqlQuery->query($sql, [
            'author_id' => $userId,
        ]);

        return $result->asObjects(UserLink::class);
    }
}
