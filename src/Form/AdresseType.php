<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Entrer votre adresse',
                'attr' => [
                    'placeholder' => 'Entrer votre adresse'
                ]
            ])
            ->add('postcode', HiddenType::class)
            ->add('city', HiddenType::class)
            ->add('region', HiddenType::class)
            ->add('lat', HiddenType::class)
            ->add('longi', HiddenType::class)
            ->add('location', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
