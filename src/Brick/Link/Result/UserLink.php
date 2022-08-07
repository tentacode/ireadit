<?php

declare(strict_types=1);

namespace App\Brick\Link\Result;

class UserLink
{
    public function __construct(
        public string $title,
        public string $url,
        public string $imageUrl,
        public string $description,
    ) {
    }
}
