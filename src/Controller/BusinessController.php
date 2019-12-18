<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Businessplan;
use App\Form\BusinessFormType;

class BusinessController extends AbstractController
{
    /**
     * @Route("dashboard/creer-business-plan", name="businessplan_create")
     */
    public function create(Request $request)
    {
        $business=new Businessplan();
        $form = $this->createForm(BusinessFormType::class, $business);

          $form->handleRequest($request);
          $user = $this->get('security.token_storage')->getToken()->getUser();
          
          if($form->isSubmitted() && $form->isValid()){
   
             $business=$form->getData();
             $business->setUser($user);
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($business);
             $entityManager->flush();

              return $this->redirectToRoute('dashboard');
              
          }
         // dump($business);die();
          return $this->render('business/create.html.twig',['form' => $form->createView()]);
     
    }
}
