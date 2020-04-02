<?php

namespace App\Form;

use App\Validator\Constraints\Email as ConstraintsEmail;

use Symfony\Component\Form\AbstractType;
use App\Entity\VariableInvoicing;
use App\Validator\Constraints\Collections as ConstraintsCollection;
use App\Validator\Constraints\Disbursement as ConstraintsDisbursement;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Validator\Constraints\Unique as ConstraintsUnique;
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
      
           
       ->add('name', TextType::class,['constraints'=> [new ConstraintsUnique()]]
       )
       ->add('vat',NumberType::class)
        ->add('productreceipt', CollectionType::class,array(
            'entry_type'=>CollectionType::class,
            'error_bubbling' => false,
            'constraints'=> [new ConstraintsCollection()]
        ))
        
           
        ->add('purchasecostofsales',CollectionType::class,array(
            'entry_type'=>NumberType::class,
            'error_bubbling' => false,
            'constraints' => [new Assert\All([new Assert\Range([
                'min' => 1,
                'max' => 100,
                'minMessage' => 'les valeur doit être entre 1 et 100',
                'maxMessage' => 'les valeur doit être entre 1 et 100'
                ])])]
        ))
        ->add('vatonpurchase',NumberType::class)
        ->add('purchasedisbursement',CollectionType::class,array(
            'entry_type'=>CollectionType::class,
            'error_bubbling' => false,
            'constraints'=> [new ConstraintsDisbursement()]
            
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
