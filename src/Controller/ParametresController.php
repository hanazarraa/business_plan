<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class ParametresController extends AbstractController
{


    /**
     * @Route("/MonCompte", name="parametres")
     */
    
    
    public function ParametresAction(){
        return $this->render('parametres.html.twig');
    }
    /**
     * @Route("/MonCompte/change_langue", name="change_langue")
     */
    
    
    public function Change_langue(Request $request){
        if($request->getLocale()=='fr'){
       
            $this->container->get('request')->setLocale('en');
        }else{
            $this->container->get('request')->setLocale('fr');

        }
        
     
      
        
        return $this->redirectToRoute($request->getLocale());
            
        

    }
     /**
     * @Route("/MonCompte/change_password", name="change_password")
     */
    public function changepassword(Request $request,  UserPasswordEncoderInterface $passwordEncoder): Response
    {
       
      
        $em = $this->getDoctrine()->getManager();
       
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        
      
        

        $url="https://127.0.0.1:8000/change-password/";
        if ($form->isSubmitted() && $form->isValid()) {
           
           
        
            $user=$this->getUser();
         
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $em->flush();
                return $this->redirectToRoute('parametres');
            }
         
            
            
       
       
    
   

    



return $this->render('change_password.html.twig', [
    'form' => $form->createView()
   
]);
    }

}