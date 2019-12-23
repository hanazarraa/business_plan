<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\Constraints\Email as ConstraintsEmail;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,array(
                'mapped'=>true,
                'constraints'=> [new ConstraintsEmail()]
            ))
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'agreeTerms.true',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'passwords.match',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'constraints' => [
                    new NotBlank([
                        'message' =>'password.NotBlank',
                    ]),
                    new Length([
                        'min'=>6,
                        'minMessage'=>'Password.minlength',
                        'max' => 4096,
                    ]),
                    ],
            ]);
            
            $builder->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptcha'
              ))
              ->add('agreeTerms',CheckboxType::class)
              ->add('submit',SubmitType::class)
        
           
           
           
           
             // ->add('agreeTerms',CheckboxType::class)
              ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
