<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Moncompteadmin;
use App\Form\ChangePasswordType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Doctrine\ORM\EntityManagerInterface;
class ParametresController extends AbstractController
{


    /**
     * @Route("/dashboard/MonCompte", name="parametres")
     */
    public function ParametresAction(){
        return $this->render('parametres.html.twig');
    }
    /**
     * @Route("admin/dashboard/Profile", name="parametresadmin")
     */
    public function ParametresAdminAction(Request $request , EntityManagerInterface $em,UserPasswordEncoderInterface $passwordEncoder){
        $user =new User();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(Moncompteadmin::class, $user);
         
          $form->handleRequest($request);
          

          if($form->isSubmitted() && $form->isValid()){
            
            
           
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    
                    $form->get('password')->getData()
                )
            );
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Admin updated!');
            return $this->redirectToRoute('dashboard');
           
              
          }
         // dump($business);die();
     
        return $this->render('admin/parametresadmin.html.twig',['form' => $form->createView(),'user' => $user]);
    }
    /**
     * @Route("/MonCompte/change_langue", name="change_langue")
     */
    
    public function change_langue(Request $request){
    if( $this->get('session')->get('_locale')=='fr'){
       
        $this->get('session')->set('_locale', 'en');
    
         // var_dump($this->get('session')->get('_locale'));
       
    
           
        
        }else{
          
            $this->get('session')->set('_locale', 'fr');

            //$this->container->get('request')->setLocale('fr');

        }
       // print_r($request->getLocale());
     
      return $this->redirectToRoute("{_locale}");
     
     
            
        


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