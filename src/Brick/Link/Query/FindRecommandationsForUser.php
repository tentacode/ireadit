<?php

declare(strict_types=1);

namespace App\Brick\Link\Query;

use App\Brick\Link\Result\UserLink;
use App\Brick\Poulpe\SqlQuery;

class FindRecommandationsForUser
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
            JOIN link_event read_other_users
                ON read_other_users.link_id = link.id
                AND read_other_users.author_id <> :author_id
                AND read_other_users.type = 'read'
            LEFT JOIN link_event added_user
                ON added_user.link_id = link.id
                AND added_user.author_id = :author_id
                AND added_user.type = 'added'
            WHERE added_user.id IS NULL
            GROUP BY link.id
            ORDER BY random()
        CODE_SAMPLE;

        $result = $this->sqlQuery->query($sql, [
            'author_id' => $userId,
        ]);

        return $result->asObjects(UserLink::class);
    }
}
