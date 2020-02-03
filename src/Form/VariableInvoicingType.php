<?php

namespace App\Form;

use App\Validator\Constraints\Email as ConstraintsEmail;

use Symfony\Component\Form\AbstractType;
use App\Entity\VariableInvoicing;
use App\Validator\Constraints\Collections as ConstraintsCollection;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;



class VariableInvoicingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      
           
       ->add('name', TextType::class
       )
       ->add('vat',NumberType::class)
        ->add('productreceipt', CollectionType::class,array(
            'entry_type'=>CollectionType::class,
            'constraints'=> [new ConstraintsCollection()]
        ))
        
           
        ->add('purchasecostofsales',CollectionType::class,array(
            'entry_type'=>NumberType::class
        ))
        ->add('vatonpurchase',NumberType::class)
        ->add('purchasedisbursement',CollectionType::class,array(
            'entry_type'=>CollectionType::class,
            'constraints'=> [new ConstraintsCollection()]
            
        ))
        ->add('submit',SubmitType::class)
        

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => VariableInvoicing::class,
            'csrf_protection' => false,
         
        ]);
    }
    
}
