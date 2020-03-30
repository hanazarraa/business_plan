<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class UniqueValidator extends ConstraintValidator
{
    
    public function validate($name, Constraint $constraint){
        //$listofname = ['azzzz','qsdqsdqd','ahmed'] ;
        //dump(in_array($name, $this->listofname));die();
        if(preg_match('/[^a-z_\-0-9]/i', $name)==1) {
     
       // if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('name')
                ->addViolation();
        //}
   }
}
}
