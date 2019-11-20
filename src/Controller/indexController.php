<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function indexAction(){
        return $this->render('index.html.twig');
    }
    /**
     * @Route("/fr", name="fr")
     */
    public function frAction(){
        return $this->render('fr.html.twig');
    }
     /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(){
        return $this->render('dashboard.html.twig');
    }
     /**
     * @Route("/MonCompte", name="parametres")
     */
    public function ParametresAction(){
        return $this->render('parametres.html.twig');
    }

    
}