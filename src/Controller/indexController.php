<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
 
class indexController extends AbstractController
{

     
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request){
      
        return $this->render("index.html.twig");
      //  return $this->render('index.html.twig');
    }
   
    /**
     * @Route("/{_locale}",name="home",requirements={"_locale"="en|fr"})
     */
    public function locale(Request $request){
        $locale=$request->getLocale();
        
        return $this->render("fr.html.twig");
      
    }
    

    
}