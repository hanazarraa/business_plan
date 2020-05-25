<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SalesRepository;
use App\Repository\SalesdetailledRepository;
use App\Repository\ProductRepository;
use App\Entity\CompteResultat;
use App\Form\CompteResultatFormType;
/**
* @Route("/{_locale}/dashboard/my-business-plan/compte-resultat")
 */
class CompteresultatController extends AbstractController
{
    /**
     * @Route("/", name="compteresultat")
     */
    public function index(Request $request ,ProductRepository $productRepository ,SalesRepository $SalesRepository,SalesdetailledRepository $SalesdetailledRepository )
    {
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $compteresultat = $entityManager->getRepository(CompteResultat::class)->findByBusinessplan($businessSession);
       
        $years = $businessSession->getNumberofyears();
        $response = $this->forward('App\Controller\SalesController::sales', [
            'request'  => $request,
            'productRepository' => $productRepository,
            'SalesRepository' => $SalesRepository,
        ]); 
        $response = $this->forward('App\Controller\PurchaseController::index', [
            'SalesdetailledRepository'  => $SalesdetailledRepository,
            'productRepository' => $productRepository,
            'request'  => $request,
            'SalesRepository' => $SalesRepository,
        ]);
        $finalCA = SalesController::getfinalca();
        $purchase = PurchaseController::getlistpurchase();
        for($x = 0 ; $x < $years ; $x++){
            for($i = 0 ; $i < 12 ; $i++){
              $SumfinalCAperMouth[$x][$i] =  0 ;}}   

         foreach($finalCA as $key=>$value){
           for($x = 0 ; $x < $years ; $x++){
            for($i = 0 ; $i < 12 ; $i++){
              $SumfinalCAperMouth[$x][$i] +=  $finalCA[$key][$x][$i] ;}}}
         
              for($x = 0 ; $x < $years ; $x++){
               $totalCA[$x] = array_sum($SumfinalCAperMouth[$x]);
              }
     
           $form = $this->createForm(CompteResultatFormType::class , $compteresultat[0]);
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()){
              $compteresultat = $form->getData();
              $entityManager->flush();
          }
          $compteresultat = $entityManager->getRepository(CompteResultat::class)->findByBusinessplan($businessSession);
          $RD = $compteresultat[0]->getRD();
          $tauximport = $compteresultat[0]->getTauximpot();
          $creditimpot = $compteresultat[0]->getCreditimpot();
           //Marge commercial 
        for($x = 0 ; $x < $years ; $x++){
            $margecommercial[$x] =  ($totalCA[$x] + $RD[$x] )- $purchase[$x]  ;
           }
       //dump($margecommercial);die();
        return $this->render('compteresultat/index.html.twig', ['RD' => $RD,
            'business' => $businessSession, 'totalCA'=> $totalCA, 'margecommercial' => $margecommercial,
            'form' => $form->createView() ,
            ]);
        
    }
}
