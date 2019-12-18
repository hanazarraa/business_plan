<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class EmailValidator extends ConstraintValidator
{
   
    public function validate($email, Constraint $constraint){
        
        if(!preg_match("/^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})/i", $email)) {
     
       // if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('email')
                ->addViolation();
        //}
   }
}
}
