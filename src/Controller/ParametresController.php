<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * @Route("/MonCompte",name="myaccount")
 */
class ParametresController extends AbstractController
{


    /**
     * @Route("/", name="parametres")
     */
    
    
    public function ParametresAction(){
        return $this->render('parametres.html.twig');
    }
     /**
     * @Route("/change_password", name="change_password")
     */
    public function changepassword(Request $request,  UserPasswordEncoderInterface $passwordEncoder): Response
    {
       
      
        $em = $this->getDoctrine()->getManager();
       
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        
      
        

        $url="https://127.0.0.1:8000/change-password/";
        if ($form->isSubmitted() && $form->isValid()) {
           
           
        
          /*  if($user === null) {
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
         
            
            */
       
       
    
   

    

}

return $this->render('change_password.html.twig', [
    'form' => $form->createView(),
    'url'=>$url
]);
    }
}