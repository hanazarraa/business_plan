<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Businessplan;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Sales;
use App\Form\BusinessFormType;

class BusinessController extends AbstractController
{
    
    /**
     * @Route("/{_locale}/dashboard/creer-business-plan", name="businessplan_create")
     */
    public function create(Request $request)
    {
        $business=new Businessplan();
        $sales= new Sales();
        $form = $this->createForm(BusinessFormType::class, $business);

          $form->handleRequest($request);
          $user = $this->get('security.token_storage')->getToken()->getUser();
          
          if($form->isSubmitted() && $form->isValid()){
   
             $business=$form->getData();
             $business->setUser($user);
             $business->setSales($sales);
             $business->setCode("".strtoupper(bin2hex(openssl_random_pseudo_bytes(32))));
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($business);
             $entityManager->flush();

              return $this->redirectToRoute('dashboard');
              
          }
         // dump($business);die();
          return $this->render('business/create.html.twig',['form' => $form->createView()]);
     
    }
    /**
     * @Route("/{_locale}/dashboard/mybusinessplan/{code}", name="monbuisnessplan")
     * 
     */
    public function show(Request $request,$code)
    {
        $business = new Businessplan();
        $sales = new Sales();   
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $business = $em->getRepository(Businessplan::class)->findOneByCode($code);
        if($business->getUser() == $user){
            $this->container->get('session')->set('business', $business); 
            return $this->redirectToRoute('mybusinessplan');
        }
        
      //  $product->setBusinessplan($business);
       // dump($product);die();
      // return $this->render('business/monbuisness.html.twig',['business' => $business]);
       return $this->redirectToRoute('dashboard');

    }
     /**
     * @Route("/{_locale}/dashboard/mybusinessplan", name="mybusinessplan")
     * 
     */
    public function dashboard(Request $request){
       $businessSession =$this->container->get('session')->get('business');
       
        return $this->render('business/monbuisness.html.twig',['business' => $businessSession]);
    }

}
