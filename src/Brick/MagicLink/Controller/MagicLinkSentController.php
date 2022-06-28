<?php

declare(strict_types=1);

namespace App\Brick\MagicLink\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagicLinkSentController extends AbstractController
{
    #[Route('/magic-link/sent', methods: ['GET'], name: 'magic_link_sent')]
    public function __invoke(Request $request): Response
    {
        return $this->render('magic_link/magic_link_sent.html.twig', [
            'provider_url' => $request->query->get('provider_url'),
        ]);
    }
}
