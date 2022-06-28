<?php

declare(strict_types=1);

namespace App\Brick\MagicLink\Controller;

use App\Brick\MagicLink\Form\SendMagicLinkType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestMagicLinkController extends AbstractController
{
    #[Route('/magic-link', methods: ['GET'], name: 'request_magic_link')]
    public function __invoke(): Response
    {
        $magicLinkForm = $this->createForm(SendMagicLinkType::class);

        return $this->render('magic_link/request_magic_link.html.twig', [
            'magic_link_form' => $magicLinkForm->createView(),
        ]);
    }
}
