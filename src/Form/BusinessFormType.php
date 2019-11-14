<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class BusinessFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('nb_periodes', ChoiceType::class,array(
                'choices'=>array(
                    '2'=>'2',
                    '3'=>'3',
                    '4'=>'4',
                    '5'=>'5',
                    '6'=>'6',
                    '7'=>'7',
                    '8'=>'8',
                    '9'=>'9',
                    '10'=>'10',

                )
            ))
            ->add('global_only',ChoiceType::class,array(
                'choices'=>array(
                    'Classic (recommended)'=>'0',
                    'Global (for a quick draft)' =>'1'	
                )
            ))
            ->add('devise',TextType::class,array('label'=>'Currency'))
            
            ->add('format',ChoiceType::class,array(
                'choices'=>array(
                    'FR'=>'FR',
                    'US'=>'US',
                )
            ))
         
            ->add('annee_debut',TextType::class)
            ->add('mois_debut',ChoiceType::class,array(
                'choices'=>array(
                    'Jan.'=>'1',
                    'Feb.'=>'2',
                    'March.'=>'3',
                    'April.'=>'4',
                    'May.'=>'5',
                    'June.'=>'6',
                    'July.'=>'7',
                    'Aug.'=>'8',
                    'Sep.'=>'9',
                    'Oct.'=>'10',
                    'Nov.'=>'11',
                    'Dec.'=>'12',

                )
            ))
            ->add('societe_elements_anterieurs',ChoiceType::class,array(
                'choices'=>array(
                    'No'=>'0',
                    'Yes'=>'1',
                )
            ))
            ->add('tva',TextType::class)
            ->add('nom_societe',TextType::class)
            ->add('OK', SubmitType::class)
        ;
    }
   
}