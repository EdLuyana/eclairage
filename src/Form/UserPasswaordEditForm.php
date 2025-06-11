<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe ne correspondent pas.',
            'first_options'  => ['label' => 'Nouveau mot de passe'],
            'second_options' => ['label' => 'Confirmer le mot de passe'],
            'mapped' => false,
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(min: 6),
            ],
        ]);
    }
}
