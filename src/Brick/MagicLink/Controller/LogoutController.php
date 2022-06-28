<?php

declare(strict_types=1);

namespace App\Brick\MagicLink\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'logout')]
    public function __invoke(): Response
    {
        // controller can be blank: it will never be called!
        throw new \Exception('This should never be reached!');
    }
}
