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

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Rentrez votre prénom', 
                'constraints' => new Length([
                    'min' => 4, 
                    'max' => 50,
                ]),
                'required' => true,
                'attr' => [
                    'placeholder' => "Prénom"
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => "Rentrez votre nom de famille",
                'constraints' => new Length([
                    'min' => 4, 
                    'max' => 50
                ]),
                'required' => true,
                'attr' => [
                    'placeholder' => "Nom de famille"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Rentrez votre email",
                'constraints' => new Length([
                    'min' => 4, 
                    'max' => 50
                ]),
                'required' => true,
                'attr' => [
                    'placeholder' => "email"
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => new Length([
                    'min' => 4, 
                    'max' => 50
                ]),
                'invalid_message' => 'Le mot de passe et la validation doivent être identique.',
                'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => "Entrez un mot de passe",
                    'attr' => [
                        'placeholder' => "Tapez votre mot de passe"
                    ],
                ],
                'second_options' => [
                    'label' => "Confirmez le mot de passe",
                    'attr' => [
                        'placeholder' => "Tapez votre mot de passe"
                    ],
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Inscription", 
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
