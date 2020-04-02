<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use App\Entity\ReccuringInvoicing;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Validator\Constraints\Unique as ConstraintsUnique;


class ReccuringInvoicingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      
           
       ->add('name', TextType::class,[ 'constraints'=> [new ConstraintsUnique()]]
       )
       ->add('saleprice',NumberType::class)
        ->add('vat', NumberType::class)         
        ->add('purchasecostofsales',NumberType::class)
        ->add('vatonpurchases',NumberType::class)
        ->add('periodicity',ChoiceType::class,array(
            'choices'=>array(
                'Mensuel'=>'1',
                'Trimestriel'=>'3',
                'Semestriel'=>'6',
                'Annuel'=>'12'
                )

        ))
        ->add('firstoccurence',ChoiceType::class,array(
            'choices'=>array(
                'Immédiate'=>'0',
                'Dans 1 mois'=>'1',
                'Dans 2 mois'=>'2',
                'Dans 3 mois'=>'3',
                'Dans 4 mois'=>'4',
                'Dans 5 mois'=>'5',
                'Dans 6 mois'=>'6',
                'Dans 7 mois'=>'7',
                'Dans 8 mois'=>'8',
                'Dans 9 mois'=>'9',
                'Dans 10 mois'=>'10',
                'Dans 11 mois'=>'11',
                'Dans 12 mois'=>'12',
                )



        ))
        ->add('permanent',CheckboxType::class,
        array(
            'required' => false,
            'attr' => array('checked'   => 'checked')
        ))
        ->add('numberofoccurences',IntegerType::class,
        array('required' => false)
        )
        ->add('submit',SubmitType::class)
        

        ;
    }
   /* public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => VariableInvoicing::class,
            'csrf_protection' => false,
         
        ]);
    }*/
    
}
