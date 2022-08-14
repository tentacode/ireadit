<?php

declare(strict_types=1);

namespace App\Brick\Link\Controller;

use App\Brick\Link\Query\FindRecommandationsForUser;
use App\Brick\Security\LoggedInUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecommandationsController extends AbstractController
{
    public function __construct(
        private FindRecommandationsForUser $findRecommandationsForUser
    ) {
    }

    #[Route('/links/recommandations', name: 'recommandations')]
    public function __invoke(LoggedInUser $loggedInUser): Response
    {
        $links = $this->findRecommandationsForUser->__invoke(
            userId: $loggedInUser->getUserId()
        );

        // @TODO : read template
        return $this->render('links/recommandations.html.twig', [
            'links' => $links,
        ]);
    }
}
