<?php

declare(strict_types=1);

namespace App\Brick\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

class LoggedInUserFactory
{
    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    public function __invoke(): LoggedInUser
    {
        Assert::notNull($this->tokenStorage);
        Assert::notNull($this->tokenStorage->getToken());

        $user = $this->tokenStorage->getToken()
            ->getUser();
        Assert::isInstanceOf($user, User::class);
        Assert::notNull($user->getId());

        return new LoggedInUser($user);
    }
}
