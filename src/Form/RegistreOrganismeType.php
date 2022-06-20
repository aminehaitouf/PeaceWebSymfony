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

                    'placeholder' => 'Entrer votre numéro de téléphone'

                ]

            ])
            ->add('domination', TextType::class, [
                'required' => true,
                'label' => 'Votre dénomination',
                'attr' => [
                    'placeholder' => 'Entrer dénomination organisme'
                ]
            ])
            ->add('siret', TextType::class, [
                'required' => true,
                'label' => 'Votre SIRET',
                'attr' => [
                    'placeholder' => 'Entrer votre SIRET'
                ]
            ])
            
            ->add('domaine', ChoiceType::class, [
                'required' => true,
                'label' => "Votre domaine d'activité",
                'choices'  => [
                    'Salon de coiffure' => 'Salon de coiffure',
                    'Salon de beauté' => 'Salon de beauté',
                    'Restaurant' => 'Restaurant',
               
                ],
            ])
            ->add('adresse', AdresseType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrer l\'adresse de votre établissement'
                ]

            ])
            ->add('association',ChoiceType::class, [
                'label' => "Votre association",
                'choice_label' => 'domination',
                'choices' => $this->association,
                
            ])
            ->add('illustration', FileType::class, [

                'label' => 'Sélectionner une photo',

                'mapped' => false,

                'required' => true

            ])

            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Votre email',
                'attr' => [
                    'placeholder' => 'Entrer votre email'
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
