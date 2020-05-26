<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SalesRepository;
use App\Repository\SalesdetailledRepository;
use App\Repository\GeneralexpensesRepository;
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
    public function index(Request $request ,ProductRepository $productRepository ,SalesRepository $SalesRepository,SalesdetailledRepository $SalesdetailledRepository,GeneralexpensesRepository $generalrep )
    {
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $exsist = True ;
        $compteresultat = $entityManager->getRepository(CompteResultat::class)->findByBusinessplan($businessSession);
        $years = $businessSession->getNumberofyears();
        for($x = 0 ; $x < $years ; $x++){
        
        $RD[$x] = "0.00";
        $tauximport[$x] ="0.00";
        $creditimpot[$x]  ="0.00";}
        if($compteresultat ==[]){
           $exsist = False ;
           $compteresultat = new CompteResultat();
           $compteresultat->setRD($RD);
           $compteresultat->setTauximpot($tauximport);
           $compteresultat->setCreditimpot($creditimpot);
           $compteresultat->setBusinessplan($businessSession);
       }
        
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
        $response = $this->forward('App\Controller\GeneralexpensesController::index', [
            'request'  => $request,
            'GeneralexpensesRepository'  => $generalrep,
                
        ]);
        $response = $this->forward('App\Controller\StaffController::index', [
            'request'  => $request,
            'productRepository' => $productRepository,
            'SalesRepository' => $SalesRepository,
                
        ]);
        $response = $this->forward('App\Controller\DepreciationController::index', [
      
        ]);
        $finalCA = SalesController::getfinalca();
        $purchase = PurchaseController::getlistpurchase();
        $generalexpense = GeneralexpensesController::getpurchase();
        $staff = StaffController::getstaff();
        $depreciation = DepreciationController::getdepreciation();
        if($staff == null){ for($x = 0 ; $x < $years ; $x++){
            $staff[$x] = "0.00";
        }}
        if($depreciation == null){ for($x = 0 ; $x < $years ; $x++){
            $depreciation[$x] = "0.00";
        }}

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
            
            if($exsist == true){
           $form = $this->createForm(CompteResultatFormType::class , $compteresultat[0]);}
           else{
        $form = $this->createForm(CompteResultatFormType::class , $compteresultat);
           }
          
           $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()){
              $compteresultat = $form->getData();
              if($exsist == false){
                $entityManager->merge($compteresultat);
              }
              $entityManager->flush();
          }
          $compteresultat = $entityManager->getRepository(CompteResultat::class)->findByBusinessplan($businessSession);
          if($compteresultat != []){
          $RD = $compteresultat[0]->getRD();
          $tauximport = $compteresultat[0]->getTauximpot();
          $creditimpot = $compteresultat[0]->getCreditimpot();}
           //Marge commercial && total produit
        for($x = 0 ; $x < $years ; $x++){
            $margecommercial[$x] =  ($totalCA[$x] + $RD[$x] )- $purchase[$x]  ;
            $totalproduit[$x] = $totalCA[$x] + $RD[$x] ; 
           }
         
       //dump($margecommercial);die();
        return $this->render('compteresultat/index.html.twig', ['RD' => $RD, 'creditimpot' => $creditimpot,
            'business' => $businessSession, 'totalCA'=> $totalCA, 'margecommercial' => $margecommercial,
            'form' => $form->createView() , 'totalproduit' => $totalproduit, 'totalachat' => $purchase, 'generalexpense' => $generalexpense ,
            'staff' => $staff, 'depreciation' => $depreciation,
            ]);
        
    }
}
