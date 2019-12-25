<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/{_locale}/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils,Request $request): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $locale=$request->getLocale();

        //return $this->redirectToRoute("{_locale}",['last_username' => $lastUsername, 'error' => $error]);
        return $this->render("security/login.html.twig", ['last_username' => $lastUsername, 'error' => $error]);

        
            
           // return $this->render('en.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

        
        //return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
     /**
      *@Route("/{_locale}/admin/login", name="loginadmin")    */
    public function loginAdmin(AuthenticationUtils $authenticationUtils): Response
      {  $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        // return $this->render('fr.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        return $this->render('security/login_admin.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        return $this->redirectToRoute("{_locale}");
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
