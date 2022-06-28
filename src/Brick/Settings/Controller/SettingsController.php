<?php

declare(strict_types=1);

namespace App\Brick\Settings\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    #[Route('/account/settings', name: 'settings')]
    public function __invoke(): Response
    {
        return $this->render('settings/settings.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }
}
