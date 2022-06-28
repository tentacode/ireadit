<?php

declare(strict_types=1);

namespace App\Brick\MagicLink\Controller;

use App\Brick\MagicLink\Form\SendMagicLinkType;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Webmozart\Assert\Assert;

class SendMagicLinkController extends AbstractController
{
    #[Route('/magic-link', methods: ['POST'], name: 'send_magic_link')]
    public function __invoke(
        MailerInterface $mailer,
        LoginLinkHandlerInterface $loginLinkHandler,
        UserRepository $userRepository,
        Request $request
    ): Response {
        $magicLinkForm = $this->createForm(SendMagicLinkType::class);
        $magicLinkForm->handleRequest($request);

        if ($magicLinkForm->isSubmitted() && $magicLinkForm->isValid()) {
            $dataUser = $magicLinkForm->getData();
            Assert::isArray($dataUser);

            Assert::keyExists($dataUser, 'email');
            Assert::email($dataUser['email']);

            $userEmail = $dataUser['email'];

            $user = $userRepository->findOneBy([
                'email' => $userEmail,
            ]);

            if ($user instanceof User) {
                $loginLinkDetails = $loginLinkHandler->createLoginLink($user);

                $registrationEmail = (new TemplatedEmail())
                    ->to(new Address($user->getEmail(), $user->getUsername()))
                    ->subject('Votre lien magique de connexion à ireadit 🔑')
                    ->htmlTemplate('email/magic_link/magic_link.html.twig')
                    ->textTemplate('email/magic_link/magic_link.txt.twig')
                    ->context([
                        'username' => $user->getUsername(),
                        'magic_link_url' => $loginLinkDetails->getUrl(),
                    ])
                ;

                $mailer->send($registrationEmail);
            } else {
                $user = new User();
                $user->setEmail($userEmail);
            }

            return $this->redirectToRoute('magic_link_sent', [
                'provider_url' => $user->getEmailProviderUrl(),
            ]);
        }

        return $this->render('magic_link/request_magic_link.html.twig', [
            'magic_link_form' => $magicLinkForm->createView(),
        ]);
    }
}
