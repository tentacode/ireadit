<?php

declare(strict_types=1);

namespace App\Brick\Registration\Controller;

use App\Brick\Registration\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterFormDisplayController extends AbstractController
{
    #[Route('/register', methods: ['GET'], name: 'register')]
    public function __invoke(): Response
    {
        $registrationForm = $this->createForm(RegistrationType::class);

        return $this->render('registration/register_form.html.twig', [
            'registration_form' => $registrationForm->createView(),
        ]);
    }
}
