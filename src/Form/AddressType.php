<?php

namespace App\Form;

use App\Entity\Address;
use Doctrine\DBAL\Types\TextType as TypesTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Quel nom souhaitez-vous donner à votre adresse ?',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Rentrez votre nom'
                ]
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Rentrez votre prénom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre nom de société',
                'attr' => [
                    'placeholder' => 'Rentrez le nom votre société'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'attr' => [
                    'placeholder' => '8 rue des lilas'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre code postal', 
                'attr' => [
                    'placeholder' => 'Rentrez votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Rentrez votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays',
                'attr' => [
                    'placeholder' => 'Rentrez votre pays'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Votre téléphone',
                'attr' => [
                    'placeholder' => 'Rentrez votre mobile'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider votre adresse',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
