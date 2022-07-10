<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_it_gets_the_email_provider(): void
    {
        $user = new User;
        $user->setEmail('hello@example.com');

        $this->assertEquals('https://example.com', $user->getEmailProviderUrl());
    }
}
