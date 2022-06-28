<?php

declare(strict_types=1);

namespace App\Brick\Registration\Controller;

use App\Brick\Registration\Exception\RegistrationValidationException;
use App\Entity\RegistrationStatus;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class RegisterValidateEmailController extends AbstractController
{
    #[Route('/register/validate/{username}/{validationHash}', name: 'register_validate')]
    public function __invoke(
        string $username,
        string $validationHash,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        Request $request,
        UserCheckerInterface $checker,
        UserAuthenticatorInterface $userAuthenticator,
        FormLoginAuthenticator $formLoginAuthenticator
    ): RedirectResponse {
        $user = $userRepository->findOneBy([
            'username' => $username,
        ]);

        if (! $user) {
            throw new RegistrationValidationException(sprintf('User with username "%s" not found', $username));
        }
        if ($user->getValidationHash() !== $validationHash) {
            throw new RegistrationValidationException(sprintf(
                'Validation hash does "%s" does not match user "%s", expected "%s".',
                $validationHash,
                $username,
                $user->getValidationHash()
            ));
        }
        $user->setRegistrationStatus(RegistrationStatus::VALIDATED);

        $em->persist($user);
        $em->flush();

        // authenticating the user
        $checker->checkPreAuth($user);
        $userAuthenticator->authenticateUser($user, $formLoginAuthenticator, $request);

        return $this->redirectToRoute('landing');
    }
}
