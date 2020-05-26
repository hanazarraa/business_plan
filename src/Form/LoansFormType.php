<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
class LoansFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('loandate',DateType::class,[
                'widget' => 'single_text',
                
            ])
            ->add('firstpaymentdate',DateType::class,[
                'widget' => 'single_text',
                
            ] )
            ->add('amount',NumberType::class)
            ->add('taux',NumberType::class)
            ->add('duration',IntegerType::class)
            ->add('numberofpayment',ChoiceType::class,array(
                'choices'=>array(
                    'Mensuel'=>'12',
                    'Trimestriel'=>'4',
                    'Semestriel'=>'2',
                    'Annuel'=>'1',
                )
            ))
            ->add('submit',SubmitType::class);
            
    }
}