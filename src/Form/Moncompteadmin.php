<?php

namespace App\Form;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use App\Entity\User;
use App\Validator\Constraints\Email as ConstraintsEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Validator\CurrentPassword;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Moncompteadmin extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
    
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'constraints' => [
                    new NotBlank([
                        'message' =>'please enter a password',
                    ]),
                    new Length([
                        'min'=>6,
                        'minMessage'=>'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                    ],
            ])
            
          
         
              ->add('submit',SubmitType::class)
        
           
           
           
           
             // ->add('agreeTerms',CheckboxType::class)
     
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
           
        ]);
    }

}
