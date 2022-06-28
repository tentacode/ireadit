<?php

declare(strict_types=1);

namespace App\Brick\MagicLink\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkCheckController extends AbstractController
{
    #[Route('/magic-link-check', name: 'check_link')]
    public function __invoke(): Response
    {
        throw new \LogicException('This code should never be reached');
    }
}
