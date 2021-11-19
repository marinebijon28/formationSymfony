<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon adresse email'
            ])
            ->add('firstName', TextType::class, [
                'disabled' => true,
                'label' => 'Mon adresse email'
            ])
            ->add('lastName', TextType::class, [
                'disabled' => true,
                'label' => 'Mon adresse email'
            ])
            ->add('oldPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mon mot de passe actuel',
                'attr' => [
                    'placeholder' => "Rentrez votre mot de passe actuel"
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'constraints' => new Length([
                    'min' => 4, 
                    'max' => 50
                ]),
                'invalid_message' => 'Le mot de passe et la validation doivent être identique.',
                'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => "Entrez un nouveau mot de passe",
                    'attr' => [
                        'placeholder' => "Tapez votre nouveau mot de passe"
                    ],
                ],
                'second_options' => [
                    'label' => "Confirmez le nouveau mot de passe",
                    'attr' => [
                        'placeholder' => "Tapez votre nouveau mot de passe"
                    ],
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour", 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
