<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('datedebut',DateType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text', 
            ])
            ->add('datefin',DateType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text', 
                'required' => false,
            ])
            ->add('isNow',ChoiceType::class, [
                'choices' => [
                        'Non' => 0,
                        'Oui' => 1,
                        

                        
                    
                ],
                'data'=> 0,
            'multiple'=>false,
            'required' => true,
                'label' => 'Est ce que vou etes dans ce poste actuellement',
                'attr' => [
                    'placeholder' => 'Est ce que vou etes dans ce poste actuellement'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
