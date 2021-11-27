<?php

namespace App\Form;

use App\Entity\ResetPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ResetPasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [ 
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
                'label' => "Mettre à jour le mot de passes", 
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResetPassword::class,
        ]);
    }
}
