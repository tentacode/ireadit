<?php

declare(strict_types=1);

namespace App\Brick\MagicLink\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SendMagicLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\Email(),
                    new Assert\NotBlank([
                        'message' => "L'email ne doit pas être vide.",
                    ]),
                ],
                'label' => 'Votre Email',
                'attr' => [
                    'placeholder' => 'nyan@lolcat.com',
                ],
            ])
        ;
    }
}
