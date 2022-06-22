<?php

declare(strict_types=1);

namespace App\Controller\Landing;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    #[Route('/', name: 'landing')]
    public function __invoke(string $name = ''): Response
    {
        $links = [];

        return $this->render('landing/landing.html.twig', [
            'links' => $links,
        ]);
    }
}
