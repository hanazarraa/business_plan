<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\CurrentPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType ;

class UnitInvoicingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      
           
        ->add('name', TextType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'required' => true,
            
            
        ])
        ->add('sellPrice', TextType::class,array(
            'mapped'=>false,
        ))
        ->add('vat',TextType::class)
           
        ->add('product_reciept_rule',TextType::class)
        ->add('product_cost_sales',TextType::class)
        ->add('vat_purchases',TextType::class)
        ->add('purchase_disbursment_rule',TextType::class)
        ->add('submit',SubmitType::class)
        

        ;
    }

    
}
