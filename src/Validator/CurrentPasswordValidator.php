<?php

namespace App\Validator;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Annotation
 */
class CurrentPasswordValidator extends ConstraintValidator
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,TokenStorageInterface $tokenStorage)
    {
        $this->passwordEncoder=$passwordEncoder;
        $this->tokenstorage=$tokenStorage;
 
        
    }
    public function validate($password, Constraint $constraint){
        $user=$this->tokenstorage->getToken()->getUser();

       

     
    
        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('current_password')
                ->addViolation();
        }
   // }
}
}
