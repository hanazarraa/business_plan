<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SalesdetailledRepository;
use App\Repository\SalesRepository;
use App\Entity\Generalexpenses;
use App\Entity\Investments;
use App\Entity\Investmentsdetail;
class TVAController extends AbstractController
{
    /**
     * @Route("/{_locale}/dashboard/my-business-plan/tva-year-{id}", name="tva")
     */
    public function index($id,Request $request,ProductRepository $productRepository,SalesdetailledRepository $SalesdetailledRepository,SalesRepository $SalesRepository)
    {
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $products=$productRepository->findBybusinessplan($businessSession);
        $years = $businessSession->getNumberofyears();
        $defaultTVA = $businessSession->getDefaultVAT();
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($businessSession);
        $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
        $investmentsdetail = $entityManager->getRepository(Investmentsdetail::class)->findBy(['Investment' =>$investments] );
        
        $response = $this->forward('App\Controller\SalesController::test', [
            'request'  => $request,
            'id' => $id,
            'ProductRepository' => $productRepository,
                
        ]);
        $response = $this->forward('App\Controller\PurchaseController::detail', [
            'SalesdetailledRepository'  => $SalesdetailledRepository,
            'ProductRepository' => $productRepository,
            'id' => $id ,
            'Request'  => $request,
            'SalesRepository' => $SalesRepository,
        ]);
        $response = $this->forward('App\Controller\GeneralexpensesController::detail', [
            'id' => $id ,
            'request'  => $request,         
        ]);
        $saleslist = SalesController::getlist();
        $purchaselist = PurchaseController::getpurchasedetailwithname();
        $generalexpensesAdm = GeneralexpensesController::getlistAdmwithname();  
        $generalexpensesPro = GeneralexpensesController::getlistProwithname();  
        $generalexpensesCom = GeneralexpensesController::getlistComwithname();  
        $generalexpensesRec = GeneralexpensesController::getlistRecwithname();
        
        // calcul de TVA sur les ventes 
        for($i = 0 ; $i  < $years ; $i++){
            for($x = 0 ; $x  < 12 ; $x++){
            $tvasurlesventes[$i][$x] = "0.00";
            $tvasurlesachat[$i][$x]= "0.00"; 
            $fraisgeneraux[$i][$x] = "0.00";
            $fraisgenerauxetachat[$i][$x] = "0.00";
            $tvasurimmobilisation[$i][$x] = "0.00";
            }}
        foreach($products as $key=>$value ){
        $pos=0;
            $TVA = $value->getVAT();
        foreach($saleslist as $nom=>$valeur){
            
              if($nom == $value->getName()){
              foreach($valeur as $year=>$chiffre){
               for($i = 0 ; $i<12 ; $i++){
               $newList[$nom][$year][$i] = ($chiffre[$i] * $TVA) / 100;   
              }
            }}
          }
          
        }
        foreach($newList as $key=>$value){
            for($i = 0 ; $i  < $years ; $i++){
                for($x = 0 ; $x  < 12 ; $x++){
            $tvasurlesventes[$i][$x] +=  $value[$i][$x];
            }}
        }
        
       //----------calcul de TVA sur achat (TVA de l'achat + TVA frais generaux)
       foreach($products as $key=>$value ){
        if($value->__toString() == 'Unit Invoicing'){
           $TVA =  $value->getVatPurchases();}
        else if($value->__toString() == 'Variable Invoicing'){
            $TVA =  $value->getVatonpurchase();
        }
        else{
            $TVA =  $value->getVatonpurchases();
        }
       foreach($purchaselist as $name=>$valeur){
           if($name == $value->getName() ){
            foreach($valeur as $year=>$chiffre){
                for($i = 0 ; $i<12 ; $i++){
                   
                    $newPurchaseList[$name][$year][$i] = ($chiffre[$i] * $TVA) / 100;   
                   }
            }

           }
       }}
       foreach($newPurchaseList as $key=>$value){
        for($i = 0 ; $i  < $years ; $i++){
            for($x = 0 ; $x  < 12 ; $x++){
        $tvasurlesachat[$i][$x] +=  $value[$i][$x];
        }}
    }
    
      //--------------frais generaux----------------------//
      foreach($generalexpensesAdm as $year=>$value){
      foreach($value as $name=>$valeur){
         foreach($valeur  as $position=>$chiffre){
          if($name != "deplacement" || $name != "assurance"){
          if(array_key_exists($name, $generalexpensses[0]->getTVAlist())){
            $defaultTVA = $generalexpensses[0]->getTVAlist()[$name][0]; 
          }
          else{
            $defaultTVA = $businessSession->getDefaultVAT();
          }
            $fraisgeneraux[$year][$position] += ($chiffre * $defaultTVA) / 100 ;

          }}
      }
      }
      //-----------------------------------------------------------//
      foreach($generalexpensesPro as $year=>$value){
        foreach($value as $name=>$valeur){
           foreach($valeur  as $position=>$chiffre){
            if($name != "deplacement" || $name != "assurance"){
            if(array_key_exists($name, $generalexpensses[0]->getTvalistproduction())){
              $defaultTVA = $generalexpensses[0]->getTvalistproduction()[$name][0]; 
            }
            else{
              $defaultTVA = $businessSession->getDefaultVAT();
            }
              $fraisgeneraux[$year][$position] += ($chiffre * $defaultTVA) / 100 ;
  
            }}
        }}
      //-----------------------------------------------------------------//
      foreach($generalexpensesCom as $year=>$value){
        foreach($value as $name=>$valeur){
           foreach($valeur  as $position=>$chiffre){
            if($name != "deplacement" || $name != "assurance"){
            if(array_key_exists($name, $generalexpensses[0]->getTvalistcommercial())){
              $defaultTVA = $generalexpensses[0]->getTvalistcommercial()[$name][0]; 
            }
            else{
              $defaultTVA = $businessSession->getDefaultVAT();
            }
              $fraisgeneraux[$year][$position] += ($chiffre * $defaultTVA) / 100 ;
  
            }}
        }}
       //----------------------------------------------------------------//
       foreach($generalexpensesRec as $year=>$value){
        foreach($value as $name=>$valeur){
           foreach($valeur  as $position=>$chiffre){
            if($name != "deplacement" || $name != "assurance"){
            if(array_key_exists($name, $generalexpensses[0]->getTvalistrecherche())){
              $defaultTVA = $generalexpensses[0]->getTvalistrecherche()[$name][0]; 
            }
            else{
              $defaultTVA = $businessSession->getDefaultVAT();
            }
              $fraisgeneraux[$year][$position] += ($chiffre * $defaultTVA) / 100 ;
  
            }}
        }}
      //somme de tva de frais et de l'achat
      
      for($i = 0 ; $i<$years ; $i++){
       for($x=0 ; $x<12;$x++){
        $fraisgenerauxetachat[$i][$x] = $tvasurlesachat[$i][$x] +$fraisgeneraux[$i][$x] ;
       }
      }
      //------------------tva sur immobilisation-----------------------------//
      for($i = 0 ; $i<$years ;$i++){
      foreach($investmentsdetail[$i]->getAdministration() as $key=>$value){
        $tva = $investments[0]->getTvalist()[$key][0];
      foreach($value as $position=>$chiffre){
        $tvasurimmobilisation[$i][$position] +=  ($chiffre  *  $tva) / 100 ;}}
     
      foreach($investmentsdetail[$i]->getProduction() as $key=>$value){
          $tva = $investments[0]->getTvalist()[$key][0];
        foreach($value as $position=>$chiffre){
        $tvasurimmobilisation[$i][$position] +=  ($chiffre  *  $tva) / 100 ;}}  

      foreach($investmentsdetail[$i]->getSales() as $key=>$value){
          $tva = $investments[0]->getTvalist()[$key][0];
        foreach($value as $position=>$chiffre){
        $tvasurimmobilisation[$i][$position] +=  ($chiffre  *  $tva) / 100 ;}}
      
        foreach($investmentsdetail[$i]->getRecherche() as $key=>$value){
          $tva = $investments[0]->getTvalist()[$key][0];
        foreach($value as $position=>$chiffre){
        $tvasurimmobilisation[$i][$position] +=  ($chiffre  *  $tva) / 100 ;}}    
    
    }
    
        return $this->render('tva/index.html.twig', [
            'business' => $businessSession ,'tvasurventes' => $tvasurlesventes, 'id' => $id , 'fraisgenerauxetachat' => $fraisgenerauxetachat,'tvasurimmobilisation'=>$tvasurimmobilisation ,
        ]);
    }
}
