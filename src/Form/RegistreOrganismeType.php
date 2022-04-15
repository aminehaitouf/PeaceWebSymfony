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
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;


class RegistreOrganismeType extends AbstractType
{
    private $userRepository;
    private $association;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->association = $this->entityManager->createQuery("SELECT User from App\Entity\User User where User.roles like '%ROLE_ASSO%' ")->getResult();
        // dd($this->association);
    }
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
            ->add('mobile', TelType::class, [

                'label' => 'Votre numéro de téléphone',

                'attr' => [

                    'placeholder' => 'Enter votre numéro de téléphone'

                ]

            ])
            ->add('domination', TextType::class, [
                'required' => true,
                'label' => 'Domination organisme',
                'attr' => [
                    'placeholder' => 'Entrer Domination organisme'
                ]
            ])
            ->add('siret', TextType::class, [
                'required' => true,
                'label' => 'Votre SIRET',
                'attr' => [
                    'placeholder' => 'Entrer votre SIRET'
                ]
            ])
            ->add('prixMoyen', TextType::class, [
                'required' => true,
                'label' => 'Votre Prix moyen de vous produits',
                'attr' => [
                    'placeholder' => 'Prix moyen de vous produits'
                ]
            ])
            ->add('domaine', ChoiceType::class, [
                'required' => true,
                'choices'  => [
                    'Coiffeurs' => 'Coiffeurs',
                    'Salon Beauté' => 'Salon Beauté',
                    'Restaurant' => 'Restaurant',
                    'Consultant/Freelance' => 'Consultant/Freelance',
                    'Autre' => 'Autre',

               
                ],
            ])
            ->add('adresse', AdresseType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrer l\'adresse de votre établissement'
                ]

            ])
            ->add('association',ChoiceType::class, [
                'choice_label' => 'domination',
                'choices' => $this->association,
                
            ])
            ->add('illustration', FileType::class, [

                'label' => 'sélectionner une photo de magasin',

                'mapped' => false,

                'required' => true

            ])

            ->add('email', EmailType::class, [
                'required' => true,
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
            ->add('cgu', null, [
                'label' => false,
                'required' => true

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
