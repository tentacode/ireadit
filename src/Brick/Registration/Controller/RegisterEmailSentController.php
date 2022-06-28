<?php

declare(strict_types=1);

namespace App\Brick\Registration\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterEmailSentController extends AbstractController
{
    #[Route('/register/email-sent', name: 'register_email_sent')]
    public function __invoke(Request $request): Response
    {
        $providerUrl = $request->query->get('provider_url') ?? '';

        return $this->render('registration/register_email_sent.html.twig', [
            'provider_url' => $providerUrl,
        ]);
    }
}
