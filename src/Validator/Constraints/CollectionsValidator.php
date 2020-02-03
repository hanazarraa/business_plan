<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class CollectionsValidator extends ConstraintValidator
{
       
    public function validate($value, Constraint $constraint){
     
        //cette partie de code permet e calculer la somme des valeur qui doit etre egale a 100
        $index = 0;
        for($z=0 ; $z<count($value['Cash']);$z++){
          ${'val'.$z} = 0 ;
        }

        for($x=0;$x<count($value['Cash']);$x++){
       foreach($value as $item){
           
        ${'val'.$index} += intval($item[$x]);  
    }
    $index++;
    }
    //la boucle si dessous permet de verifier toutes les ligne de somme 100
    for($i=0 ; $i<count($value['Cash']);$i++){
        if(!(${'val'.$i} == 100)) {
           
            // if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
                 $this->context->buildViolation($constraint->message)
                     ->atPath('value')
                     ->addViolation();
            }
        }
        

    }
}
