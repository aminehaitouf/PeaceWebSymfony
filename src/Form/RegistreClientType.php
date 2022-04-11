<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;


class RegistreClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
   
            ->add('firstname', TextType::class, [
                'label' => 'Votre Prénom',
                'attr' => [
                    'placeholder' => 'Entrer votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre Nom',
                'attr' => [
                    'placeholder' => 'Entrer votre nom'
                ]
            ])
            ->add('mobile', TelType::class, [

                'label' => 'Votre numéro de téléphone',

                'attr' => [

                    'placeholder' => 'Enter votre numéro de téléphone'

                ]

            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre Courriel',
                'attr' => [
                    'placeholder' => 'Entrer votre courriel'
                ]
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
