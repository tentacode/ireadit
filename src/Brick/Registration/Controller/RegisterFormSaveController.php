<?php

declare(strict_types=1);

namespace App\Brick\Registration\Controller;

use App\Brick\Registration\Form\RegistrationType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

class RegisterFormSaveController extends AbstractController
{
    #[Route('/register', methods: ['POST'], name: 'register_user')]
    public function __invoke(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $user = new User();

        $registrationForm = $this->createForm(RegistrationType::class, $user);
        $registrationForm->handleRequest($request);
        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $user = $registrationForm->getData();
            Assert::isInstanceOf($user, User::class);
            Assert::stringNotEmpty($user->getEmail());
            Assert::stringNotEmpty($user->getUsername());

            $registrationEmail = (new TemplatedEmail())
                ->to(new Address($user->getEmail(), $user->getUsername()))
                ->subject('À un clic près de rejoindre ireadit ! 🚀')
                ->htmlTemplate('email/registration/validation_link.html.twig')
                ->textTemplate('email/registration/validation_link.txt.twig')
                ->context([
                    'username' => $user->getUsername(),
                    'validation_hash' => $user->getValidationHash(),
                ])
            ;

            $mailer->send($registrationEmail);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('register_email_sent', [
                'provider_url' => $user->getEmailProviderUrl(),
            ]);
        }

        return $this->render('registration/register_form.html.twig', [
            'registration_form' => $registrationForm->createView(),
        ]);
    }
}
