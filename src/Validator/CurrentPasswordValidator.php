<?php

namespace App\Validator;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
/**
 * @Annotation
 */
class CurrentPasswordValidator extends ConstraintValidator
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
        
    }
    public function validate($password, Constraint $constraint){
        if($this->geUser()){
         $pass=$this->passwordEncoder->encodePassword(
        $this->getUser(),
        $password);
        var_dump($this->getUser());
        var_dump($this->getUser()->getPassword());
        var_dump($pass);
        exit;

     
    
        if ($pass !== $this->getUser()->getPassword()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('current_password')
                ->addViolation();
        }
    }
}
}
