<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start',TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text', 
            ])
            ->add('maxSupport', TextType::class, [
                'required' => true,
                'label' => 'Le nombre du support de chaque heure',
                
            ])
            ->add('end',TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text', 
            ])
            ->add('dayon',ChoiceType::class, [
                'choices' => [
                        'Lundi' => 1,
                        'Mardi' => 2,
                        'Mercredi' => 3,
                        'Jeudi' => 4,
                        'Vendredi' => 5,
                        'Samedi' => 6,
                        'Dimanche' => 0
                        
                    
                ],
                
            'multiple'=>false,
            "expanded"=>true,
            'required' => true,
                'label' => 'Entrer les jours de repos',
                'attr' => [
                    'placeholder' => 'Entrer les jours de repos'
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
