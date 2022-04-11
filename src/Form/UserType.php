<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AdresseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'required' => true,
            'label' => 'Votre Prénom',
            'attr' => [
                'placeholder' => 'Entrer votre prénom'
            ]
        ])
        ->add('lastname', TextType::class, [
            'required' => true,
            'label' => 'Votre Nom',
            'attr' => [
                'placeholder' => 'Entrer votre nom'
            ]
        ])
        ->add('description', TextType::class, [
            'required' => true,
            'label' => 'Votre description de la boutique',
            'attr' => [
                'placeholder' => 'Entrer votre description de la boutique'
            ]
        ])
        ->add('mobile', TelType::class, [

            'label' => 'Votre numéro de téléphone',

            'attr' => [

                'placeholder' => 'Enter votre numéro de téléphone'

            ]

        ])
       
       
        ->add('domaine', ChoiceType::class, [
            'required' => true,
            'choices'  => [
                'Coiffeurs' => 'Coiffeurs',
                'Salon Beauté' => 'beaute',

           
            ],
        ])
        
        ->add('association', ChoiceType::class, [
            'required' => true,
            'choices'  => [
                'Unesef' => 'Unesef',
                'Unesco' => 'Unesco',

           
            ],
        ])
        ->add('illustration', FileType::class, [

            'label' => 'sélectionner une photo de magasin',

            'mapped' => false,
            'required'   => false,


        ])

        
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Votre mot de passe doit être identique à la confirmation.',
            'label' => 'Votre mot de passe',
            'required' => true,
            'first_options' => ['label' => 'Votre mot de passe', 'attr' => ['placeholder' => 'Entrer votre mot de passe']],
            'second_options' => ['label' => 'Répétez votre mot de passe', 'attr' => ['placeholder' => 'Entrer votre mot de passe']],

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
