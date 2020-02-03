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



class ReccuringInvoicingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      
           
       ->add('name', TextType::class
       )
       ->add('saleprice',NumberType::class)
        ->add('vat', NumberType::class)         
        ->add('purchasecostofsales',NumberType::class)
        ->add('vatonpurchases',NumberType::class)
        ->add('periodicity',ChoiceType::class,array(
            'choices'=>array(
                'Mounthly'=>'1',
                'Quarterly'=>'3',
                'Half-Yearly'=>'6',
                'Yearly'=>'12'
                )

        ))
        ->add('firstoccurence',ChoiceType::class,array(
            'choices'=>array(
                'Immediate'=>'0',
                'In 1 month(s)'=>'1',
                'In 2 month(s)'=>'2',
                'In 3 month(s)'=>'3',
                'In 4 month(s)'=>'4',
                'In 5 month(s)'=>'5',
                'In 6 month(s)'=>'6',
                'In 7 month(s)'=>'7',
                'In 8 month(s)'=>'8',
                'In 9 month(s)'=>'9',
                'In 10 month(s)'=>'10',
                'In 11 month(s)'=>'11',
                'In 12 month(s)'=>'12',
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
