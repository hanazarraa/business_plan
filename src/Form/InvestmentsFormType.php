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
class InvestmentsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->
        
        add('Name',TextType::class)
        
        ->add('Department', ChoiceType::class,array(
            'choices'=>array(
                'Administration & Gestion'=>'0',
                'Production'=>'1',
                'Commercial'=>'2',
                'Recherche & Développement'=>'3',
           

            )
        ))
        ->add('categorie', ChoiceType::class,array(
            'choices'=>array(
                'Frais d\'établissement'=>'Frais d\'établissement',
                'Autres investissements immatériels'=>'Autres investissements immatériels',
                'Constructions et agencements'=>'Constructions et agencements',
                'Matériel et mobilier'=>'Matériel et mobilier',
                'Matériel informatique'=>'Matériel informatique',
                'Véhicules'=>'Véhicules',
                'Autres investissements matériels'=>'Autres investissements matériels',
                'Cautions(non amorties)'=>'Cautions(non amorties)',
                'Participations(non amorties)'=>'Participations(non amorties)',
                'Autres investissements financiers(non amorties)'=>'Autres investissements financiers(non amorties)',
                )
        ))
        ->add('Duration',NumberType::class)
        ->add('VAT',NumberType::class)
        ->add('submit', SubmitType::class);
    }

}