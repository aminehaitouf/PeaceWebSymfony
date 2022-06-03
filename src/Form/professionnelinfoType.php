<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AdresseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class professionnelinfoType extends AbstractType
{
    private $userRepository;
    private $association;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->association = $this->entityManager->createQuery("SELECT User from App\Entity\User User where User.roles like '%ROLE_ASSO%' ")->getResult();
        // dd($this->association->getDomination());
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'required' => true,
            'label' => 'Prénom',
            'attr' => [
                'placeholder' => 'Entrer votre prénom'
            ]
        ])
        ->add('lastname', TextType::class, [
            'required' => true,
            'label' => 'Nom',
            'attr' => [
                'placeholder' => 'Entrer votre nom'
            ]
        ])
        ->add('prixMoyen', TextType::class, [
            'required' => true,
            'label' => 'Prix moyen (par réservation)',
            'attr' => [
                'placeholder' => 'Prix moyen de vos prestations'
            ]
        ])
        ->add('description', TextType::class, [
            'required' => true,
            'label' => 'Votre description de la boutique',
            'attr' => [
                'placeholder' => 'Entrer la description de votre activité'
            ]
        ])
        ->add('mobile', TelType::class, [

            'label' => 'Numéro de téléphone (portable)',

            'attr' => [

                'placeholder' => 'Entrer votre numéro de téléphone'

            ]

        ])
       
       
        // ->add('domaine', ChoiceType::class, [
        //     'required' => true,
        //     'choices'  => [
        //         'Coiffeurs' => 'Coiffeurs',
        //         'Salon Beauté' => 'Salon Beauté',
        //         'Restaurant' => 'Restaurant',
        //         'Consultant/Freelance' => 'Consultant/Freelance',
        //         'Autre' => 'Autre',

           
        //     ],
        // ])
        
        ->add('nbrheurebenevole', NumberType::class, [
            'required' => true,
            'label' => 'Votre aide bénévole (heures de bénévolat par an max.)',
            'attr' => [
                'placeholder' => 'Entrer votre nombre d\'heures de bénévolat (par an)  '
            ]
        ])
        
        ->add('done', NumberType::class, [
            'required' => true,
            'label' => 'Votre don à l\'association (par réservation)',
            'attr' => [
                'placeholder' => 'Entrer la veleur du don (par réservation)'
            ]
        ])
        ->add('association',ChoiceType::class, [
            'label' => 'Vous soutenez l\'association',
            'choice_label' => 'Association',
            'choices' => $this->association,
            
        ])
        ->add('reduction', ChoiceType::class, [
            'required' => true,
            'label' => 'Pourcentage de réduction',
            'choices'  => [
                '0 %' => 0,
                '10 %' => 10,
                '20 %' => 20,
                '30 %' => 30,
                '40 %' => 40,
                '50 %' => 50,

            ]
            
        ])
        ->add('illustration', FileType::class, [

            'label' => 'Sélectionner une photo',

            'mapped' => false,
            'required'   => false,


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
