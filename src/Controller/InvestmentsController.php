<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\GeneralexpensesRepository;
/**
* @Route("/{_locale}/dashboard/my-business-plan/Investments")
 */
class InvestmentsController extends AbstractController
{
     /**
     * @Route("/", name="investments")
     */
   public function index(){
    $businessSession =$this->container->get('session')->get('business');

    return $this->render('Investments/index.html.twig',['business'=> $businessSession]);
   }


}