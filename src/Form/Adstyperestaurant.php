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

class Adstyperestaurant extends AbstractType
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
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
        ]);
    }
}
