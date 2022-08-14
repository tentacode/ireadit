<?php

declare(strict_types=1);

namespace App\Brick\Link\Controller;

use App\Brick\Link\Query\FindReadLinksByUser;
use App\Brick\Security\LoggedInUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadLinksController extends AbstractController
{
    public function __construct(
        private FindReadLinksByUser $findReadLinksByUser
    ) {
    }

    #[Route('/links/read', name: 'read_links')]
    public function __invoke(LoggedInUser $loggedInUser): Response
    {
        $links = $this->findReadLinksByUser->__invoke(
            userId: $loggedInUser->getUserId()
        );

        // @TODO : read template
        return $this->render('links/read_links.html.twig', [
            'links' => $links,
        ]);
    }
}
