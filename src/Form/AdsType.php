<?php

namespace App\Form;

use App\Entity\Ads;
use App\Entity\Category;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdsType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
        //dd($this->security->getUser()->getCategories());
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => true,
            ])
            ->add('prix', TextType::class, [
                'required' => true,
            ])
            ->add('categorie',EntityType::class, [
                'choice_label' => 'titre',
                'choices' => $this->security->getUser()->getRoles()[0] === "ROLE_COM" ? $this->security->getUser()->getCategories() : [
                    'autre' => 'autre',

               
                ],
                'class' => Category::class,
                'placeholder' => 'Choisissez une categorie',
                'attr' => ['class' => 'select-tag']
                ])

            ->add('description', TextType::class, [
                'required' => false,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
        ]);
    }
}
