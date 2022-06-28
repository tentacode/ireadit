<?php

declare(strict_types=1);

namespace App\Implementation\Email\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

class PreviewEmailsController extends AbstractController
{
    #[Route('/preview-emails', name: 'preview_emails')]
    public function index(): Response
    {
        // cannot preview emails unless in dev environment
        Assert::inArray($this->getParameter('kernel.environment'), ['dev']);

        return $this->render('email/preview_emails/index.html.twig', [
            'controller_name' => 'PreviewEmailsController',
        ]);
    }
}
