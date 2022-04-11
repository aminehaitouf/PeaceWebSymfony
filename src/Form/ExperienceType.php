<?php

namespace App\Form;

use App\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;




class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poste')
            ->add('datedebut',DateType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text', 
            ])
            ->add('datefin',DateType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text', 
            ])
            ->add('isNow',ChoiceType::class, [
                'choices' => [
                        'Non' => 0,
                        'Oui' => 1,
                        

                        
                    
                ],
                
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
            'data_class' => Experience::class,
        ]);
    }
}
