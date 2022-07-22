<?php

namespace App\Brick\Support\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupportController extends AbstractController
{
    #[Route('/support', name: 'support')]
    public function index(): Response
    {
        return $this->render('support/support.html.twig', [
            'discord_invitation_url' => 'https://discord.gg/tRcnPjhnTv',
        ]);
    }
}
