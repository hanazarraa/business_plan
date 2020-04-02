<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class DisbursementValidator extends ConstraintValidator
{
   private $state = false ;    
    public function validate($value, Constraint $constraint){
     
        //cette partie de code permet e calculer la somme des valeur qui doit etre egale a 100
        $index = 0;
        for($z=0 ; $z<count($value['Cash']);$z++){
          ${'val'.$z} = 0 ;
        }

        for($x=0;$x<count($value['Cash']);$x++){
       foreach($value as $item){
           if($item[$x]>=0){
        ${'val'.$index} += intval($item[$x]);  }
        else {
            $this->state = true;
        }
    }
    $index++;
    }
    
    //la boucle si dessous permet de verifier toutes les ligne de somme 100
    for($i=0 ; $i<count($value['Cash']);$i++){
        if(!(${'val'.$i} == 100)) {
           
            // if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
                $this->state = true; 
            }
        }
        //dump($this->state);die();
        if($this->state == true){
            $this->context->buildViolation($constraint->message)
            ->atPath('value')
            ->addViolation();
        }

    }
}
