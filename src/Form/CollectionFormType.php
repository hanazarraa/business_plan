<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class CollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->
        
        add('Administration',CollectionType::class,[

            'entry_type'=>CollectionType::class,
            'label' => false 
        ])
        ->add('production',CollectionType::class,[

            'entry_type'=>CollectionType::class,
            'label' => false 
        ])
        ->add('sales',CollectionType::class,[

            'entry_type'=>CollectionType::class,
            'label' => false 
        ])
        ->add('recherche',CollectionType::class,[

            'entry_type'=>CollectionType::class,
            'label' => false 
        ])
        ->add('submit', SubmitType::class);
    }
}