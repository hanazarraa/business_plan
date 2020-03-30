<?php

namespace App\Form;

use App\Entity\Product;
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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\Collections as ConstraintsCollection;
use App\Validator\Constraints\Unique as ConstraintsUnique;
class UnitInvoicingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      
           
       ->add('name', TextType::class,[
        'constraints'=> [new ConstraintsUnique()]

       ])
        ->add('sellsPrice', CollectionType::class,array(
            'entry_type'=>NumberType::class,
            'error_bubbling' => false,
            'constraints' => [new Assert\All([new Assert\Positive()])]
        ))
        ->add('vat',NumberType::class)
           
        ->add('products_reciept_rule',CollectionType::class,array(
            'entry_type'=>CollectionType::class,
            'error_bubbling' => false,
            'constraints'=> [new ConstraintsCollection()]
          
            ))
        ->add('product_cost_sales',CollectionType::class,array(
            'entry_type' => NumberType::class,
            'error_bubbling' => false,
            'constraints' => [new Assert\All([new Assert\Range([
            'min' => 1,
            'max' => 100,
            'minMessage' => 'les valeur doit être entre 1 et 100',
            'maxMessage' => 'les valeur doit être entre 1 et 100'
            ])])]
        ))
        ->add('vat_purchases',NumberType::class)
        ->add('purchase_disbursment_rule',CollectionType::class,array(
            'entry_type'=>CollectionType::class ,
            'error_bubbling' => false,
            'constraints'=> [new ConstraintsCollection()]
           
        ))
        ->add('submit',SubmitType::class)
        

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'error_mapping' => [
                'sellsPrice' => 'sellsPrice',
            ],
        ]);
    }
    
    
}
