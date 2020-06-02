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
class ChargesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('forfait',NumberType::class,[])
        ->add('Taux',NumberType::class,[])
        ->add('JEI',NumberType::class,[])
        ->add('Decaissement',ChoiceType::class,array(
            'choices'=>array(
                'Mensuel' =>'1',
                'Trimestriel'=>'3',
                
                
            )
        ))
        ->add('submit', SubmitType::class);
    }
}