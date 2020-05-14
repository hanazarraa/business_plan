<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotpasswordForm;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Services\MailerService;

class RegistrationController extends AbstractController
{
    
    /**
     * @Route("/{_locale}/register", name="app_register")
     */
    public function register(AuthenticationUtils $authenticationUtils,Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerService $mailerService, \Swift_Mailer $mailer)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    
                    $form->get('password')->getData()
                )
            );
            $user->setConfirmationToken($this->generateToken());
            $mailerService->sendToken($user->getConfirmationToken(), $user->getEmail(), $user->getUsername(),'mail_confirmation.html.twig');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
           $this->addFlash('user-error', 'Votre inscription a été validée, vous aller recevoir un email de confirmation pour activer votre compte et pouvoir vous connecté');

            // do anything else you need here, like send an email
           return $this->render('confirm_account.html.twig');
            //return $this->redirectToRoute("app_login");
       // }
        }
      
       $locale=$request->getLocale();
     
       return $this->render("registration/fr/inscription.html.twig",[
            'form'=>$form->createView(),
            'errors'=>$form->getErrors(),
           // 'captcha_html' => $captcha->Html()
        ]);
    

}
 /**
     * @Route("/account/confirm/{token}/{username}", name="confirm_account")
     * @param $token
     * @param $username
     * @return Response
     */
    public function confirmAccount($token, $username): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => $username]);
        $tokenExist = $user->getConfirmationToken();
        if($token === $tokenExist) {
           $user->setConfirmationToken('');
           $user->setEnabled(true);
           $em->persist($user);
           $em->flush();
           return $this->redirectToRoute('app_login');
        }
        else{
            return $this->redirectToRoute('app_register');
        }
    }
     /**
     * @Route("/send-token-confirmation", name="send_confirmation_token")
     * @param Request $request
     * @param MailerService $mailerService
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function sendConfirmationToken(Request $request, MailerService $mailerService, \Swift_Mailer $mailer){
        $em = $this->getDoctrine()->getManager();
        $email = $request->request->get('email');
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
        if($user === null) {
            $this->addFlash('not-user-exist', 'utilisateur non trouvé');
            return $this->redirectToRoute('app_register');
        }
        $user->setConfirmationToken($this->generateToken());
        $em->persist($user);
        $em->flush();
        $token = $user->getConfirmationToken();
        $email = $user->getEmail();
        $username = $user->getUsername();
        $mailerService->sendToken($user->getConfirmationToken(), $user->getEmail(), $user->getUsername(),'mail_confirmation.html.twig');

        return $this->redirectToRoute('app_login');
    }
      /**
     * @Route("/mot-de-passe-oublier", name="forgotten_password")
     * @param Request $request
     * @param MailerService $mailerService
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws \Exception
     */
    public function forgottenPassword(Request $request, MailerService $mailerService, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(ForgotpasswordForm::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $email=$form->get('email')->getData();
            
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
          
            if($user === null) {
                $this->addFlash('user-error', 'utilisateur non trouvé');
                return $this->redirectToRoute('app_register');
            }
            $user->setTokenpassword($this->generateToken());
            $user->setCreatedtokenpassword(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $token = $user->getTokenpassword();
            $email = $user->getEmail();
            $username = $user->getUsername();

            $mailerService->sendToken($token, $email, $username, 'demande_reset.html.twig');
            return $this->render('forgot_mail.html.twig',array('email'=>$email));
        }
        return $this->render('registration/forgotpassword.html.twig',[
            'form'=>$form->createView(),
            'errors'=>$form->getErrors()
        ]);
        //return $this->render('registration/forgotpassword.html.twig');
    }

/**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
    /**
     * @Route("/reset-password/{token}", name="reset_password")
     * @param Request $request
     * @param $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function resetPassword(Request $request, $token, UserPasswordEncoderInterface $passwordEncoder): Response
    {
       
      
        $em = $this->getDoctrine()->getManager();
       
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        
      
        

        $url="https://127.0.0.1:8000/reset-password/".$token;
        if ($form->isSubmitted() && $form->isValid()) {
           
            $user = $em->getRepository(User::class)->findOneBy(['tokenpassword' => $token]);
        
            if($user === null) {
                $this->addFlash('not-user-exist', 'utilisateur non trouvé');
                return $this->redirectToRoute('app_register');
            }
            $user->setTokenpassword('');
           
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $em->flush();
                return $this->redirectToRoute('app_login');
            }
         
            
            
       
        return $this->render('registration/reset_password.html.twig', [
            'form' => $form->createView(),
            'url'=>$url
        ]);
    }

    




}