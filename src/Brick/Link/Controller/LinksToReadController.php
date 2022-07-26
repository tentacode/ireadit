<?php

declare(strict_types=1);

namespace App\Brick\Link\Controller;

use App\Repository\LinkRepository;
use function Safe\shuffle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class LinksToReadController extends AbstractController
{
    public function __construct(
        private LinkRepository $linkRepository
    ) {
    }

    #[Route('/links/to-read', name: 'links_to_read')]
    public function __invoke(): Response
    {
        $links = $this->linkRepository->findAll();
        shuffle($links);

        return $this->render('links/to_read.html.twig', [
            'links' => $links,
        ]);
    }
}
