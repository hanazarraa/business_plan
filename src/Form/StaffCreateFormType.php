<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class StaffCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Name',TextType::class,[])
        ->add('Department', ChoiceType::class,array(
            'choices'=>array(
                'Administration & Gestion'=>'0',
                'Production'=>'1',
                'Commercial'=>'2',
                'Recherche & Développement'=>'3',
           

            )
        ))
        ->add('JEI',CheckboxType::class,[
            'label'    => 'JEI',
            'required' => false,
            
            
        ])
        ->add('TNS',CheckboxType::class,[
            'label'    => 'TNS',
            'required' => false,
        ])
        ->add('Gratification',CheckboxType::class,[
            'label'    => 'Gratification',
            'required' => false, 
        ])

        ->add('ChargePatronale',NumberType::class,[])
        ->add('ChargeSalariales',NumberType::class,[])
        
        ->add('submit', SubmitType::class);
    }
}

