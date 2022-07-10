<?php

declare(strict_types=1);

namespace App\Brick\Security\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

class WhoamiController extends AbstractController
{
    #[Route('/api/whoami', name: 'api_whoami', format: 'json')]
    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();
        Assert::isInstanceOf($user, User::class);

        return new JsonResponse([
            'uuid' => $user->getUuid(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
        ]);
    }
}
