<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\RegistrationStatus;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUuid(UuidV4::fromString('92e90ebf-9613-4401-8ebc-b664560d319e'));
        $user->setEmail('gabriel@tentacode.test');
        $user->setUsername('tentacode');
        $user->setRegistrationStatus(RegistrationStatus::VALIDATED);

        $manager->persist($user);

        $manager->flush();
    }
}
