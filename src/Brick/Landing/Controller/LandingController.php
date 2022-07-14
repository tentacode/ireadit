<?php

declare(strict_types=1);

namespace App\Brick\Landing\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    #[Route('/', name: 'landing')]
    public function __invoke(): Response
    {
        return $this->render('landing/landing.html.twig');
    }
}
