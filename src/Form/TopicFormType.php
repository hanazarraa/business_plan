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
class TopicFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->
        
        add('Name',TextType::class)
        ->add('VAT',NumberType::class)
        ->add('Departement', ChoiceType::class,array(
            'choices'=>array(
                'Administration & Gestion'=>'0',
                'Production'=>'1',
                'Commercial'=>'2',
                'Recherche & Développement'=>'3',
           

            )
        ))
        ->add('submit', SubmitType::class);
    }

}