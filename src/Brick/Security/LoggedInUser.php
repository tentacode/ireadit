<?php

declare(strict_types=1);

namespace App\Brick\Security;

use App\Entity\User;
use Webmozart\Assert\Assert;

class LoggedInUser
{
    public function __construct(private User $user)
    {
    }

    public function getUserId(): int
    {
        Assert::notNull($this->user->getId());

        return $this->user->getId();
    }
}
