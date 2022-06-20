<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class BenevolatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start',TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'label' => false,
            ])

            ->add('end',TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text', 
                'label' => false,
            ])
            ->add('dayon',ChoiceType::class, [
                'choices' => [
                        'Lundi' => '1',
                        'Mardi' => '2',
                        'Mercredi' => '3',
                        'Jeudi' => '4',
                        'Vendredi' => '5',
                        'Samedi' => '6',
                        'Dimanche' => '0'
                        
                    
                ],
                
            'multiple'=>false,
            "expanded"=>true,
            'required' => true,
                'label' => false,
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
