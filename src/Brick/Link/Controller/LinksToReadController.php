<?php

declare(strict_types=1);

namespace App\Brick\Link\Controller;

use App\Brick\Link\Query\FindLinksToReadByUser;
use App\Brick\Security\LoggedInUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinksToReadController extends AbstractController
{
    public function __construct(
        private FindLinksToReadByUser $findLinksToReadByUser
    ) {
    }

    #[Route('/links/to-read', name: 'links_to_read')]
    public function __invoke(LoggedInUser $loggedInUser): Response
    {
        $links = $this->findLinksToReadByUser->__invoke(
            userId: $loggedInUser->getUserId()
        );

        return $this->render('links/to_read.html.twig', [
            'links' => $links,
        ]);
    }
}
