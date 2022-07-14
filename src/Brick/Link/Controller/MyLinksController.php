<?php

declare(strict_types=1);

namespace App\Brick\Link\Controller;

use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyLinksController extends AbstractController
{
    public function __construct(
        private LinkRepository $linkRepository
    ) {
    }

    #[Route('/my-links', name: 'my_links')]
    public function __invoke(): Response
    {
        $links = $this->linkRepository->findAll();

        return $this->render('links/my_links.html.twig', [
            'links' => $links,
        ]);
    }
}
