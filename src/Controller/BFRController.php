<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SalesdetailledRepository;
use App\Repository\ProductRepository;
use App\Repository\SalesRepository;
use Symfony\Component\HttpFoundation\Request;
/**
* @Route("/{_locale}/dashboard/my-business-plan/BFR")
 */
class BFRController extends AbstractController
{
    private $tvacredit ;
    private $tvaadecaisser;
    private $creanceFiscales ;
    private $DettesFiscales ;
    private $DettesSociale;
    private $VariationDettes ;
    private $totalwithname ;
    /**
     * @Route("/", name="BFR")
     */
    public function index(Request $request,SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,SalesRepository $SalesRepository)
    {
        $id = 1 ;
        $businessSession =$this->container->get('session')->get('business');
        $rangeofdetail  = $businessSession->getRangeofdetail();
     
       
        $this->detail($request,$SalesdetailledRepository,$productRepository,$SalesRepository,0);
       
       
        return $this->render('bfr/index.html.twig', [
            'business' => $businessSession,'tvacredit' => $this->tvacredit, 'creancefiscale' => $this->creanceFiscales
            , 'DettesFiscales' =>  $this->DettesFiscales , 'tvaapayer' =>  $this->tvaadecaisser, 'DettesSociale' => $this->DettesSociale
            ,'VariationDettes'=> $this->VariationDettes,
        ]);
    }
    /**
     * @Route("-annee-{id}", name="BFRdetail")
     */
    public function detail(Request $request,SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,SalesRepository $SalesRepository,$id){
        $businessSession =$this->container->get('session')->get('business');
        $years = $businessSession->getNumberofyears();
        $products=$productRepository->findBybusinessplan($businessSession);
        //-----------------------------Initialiser les listes de BFR--------------------------------------------------------//
        foreach($products as $product){
        for($i=0;$i<$years;$i++){
        for($x=0;$x<12;$x++){
        $Compteclientwithname[$product->getName()][$i][$x] = "0.00";
        $FinalCompteclientwithname[$product->getName()][$i][$x] ="0.00";
        }
        }}
        for($i=0;$i<$years;$i++){
            for($x=0;$x<12;$x++){
        $this->creanceFiscales[$i][$x] = "0.00";
        $this->DettesFiscales[$i][$x] = "0.00";
        $variationcreances[$i][$x] = "0.00";
        $this->DettesSociale[$i][$x] = "0.00";
         
            }
        }   
        for($i=0;$i<$years + 1;$i++){
            for($x=0;$x<12;$x++){
                $this->VariationDettes[$i][$x] = "0.00";     }}
        //-----------------------------End---------------------------------------------------------------------------------//

        $response = $this->forward('App\Controller\SalesController::receiptyears', [
            'Request' => $request,
            'SalesdetailledRepository'  => $SalesdetailledRepository,
            'ProductRepository' => $productRepository,
            'id' => $id ,
            'SalesRepository' => $SalesRepository,
        ]); 
        $response = $this->forward('App\Controller\SalesController::sales', [
            'request'  => $request,
            'productRepository' => $productRepository,
            'SalesRepository' => $SalesRepository,
        ]);  
        $response = $this->forward('App\Controller\TVAController::index', [
            'id' => $id,
            'request'  => $request,
            'ProductRepository' => $productRepository,
            'SalesdetailledRepository' => $SalesdetailledRepository,    
            'SalesRepository' => $SalesRepository,
            ]); 
        
        $response = $this->forward('App\Controller\ISController::index', [
            'id' => $id,
            'request'  => $request,
                ]);
        $response = $this->forward('App\Controller\ISController::index', [
                    'id' => $id,
                    'request'  => $request,
                        ]);
        $response = $this->forward('App\Controller\StaffController::detail', [
                            'Request'  => $request,
                            'id' => $id,
                            'ProductRepository' => $productRepository,
                            'SalesRepository' => $SalesRepository,
                                
                        ]);    
        $this->totalwithname = SalesController::getTotalwithname();
        $finalCA = SalesController::getfinalca(); 
        $this->tvacredit = TVAController::getcreditTVA();
        $this->tvaadecaisser =  TVAController::getTvadecaisser();
        $isdufin = ISController::getISdufin();
        $DecaissementAdm = StaffController::getDecaissementchargesAdm();
        $DecaissementPro = StaffController::getDecaissementchargesPro();
        $DecaissementCom = StaffController::getDecaissementchargesCom();
        $DecaissementRec = StaffController::getDecaissementchargesRec();
      
        //--------------------------debut de calcul-----------------------------------------//
        //--------------------------Compte client-------------------------------------------//
       foreach($products as $product){
           $tva = $product->getVat();
       foreach($finalCA as $name=>$value){
       if($product->getName() == $name ){
       foreach($value as $position=>$month){
        foreach($month as $number=>$chiffre){
        
        $Compteclientwithname[$name][$position][$number] =  $chiffre * $tva / 100 + $chiffre ;
       }}}
       }
       }
       foreach($Compteclientwithname as $name=>$value){
       foreach($this->totalwithname as $productname=>$secondvalue){
           if($name == $productname){
           foreach($value as $year=>$chiffre){
           foreach($chiffre as $position=>$nombre){
               
            $FinalCompteclientwithname[$name][$year][$position] = $nombre  - $secondvalue[$year][$position];
           
           }
       }}}}
        //--------------------------Fin Compte client---------------------------------------//
        //--------------------------Creance TVA --------------------------------------------//
     
        //--------------------------Fin-----------------------------------------------------//

        //----------------------------Creance Fiscale---------------------------------------//
        for($i=0;$i<$years;$i++){
        for($x=0;$x<12;$x++){
            if($isdufin[$i][$x] < 0){
            $this->creanceFiscales[$i][$x] = abs($isdufin[$i][$x]);// metter  en valeur absolue
            }
            else {
            $this->DettesFiscales[$i][$x]  = $isdufin[$i][$x] ;
            }
        }
        }
       
        //----------------------------Fin---------------------------------------------------//
        //----------------------------Dettes Sociale ---------------------------------------//
        for($i=0;$i<$years;$i++){
          for($x=0;$x<11;$x++){
            
            $this->DettesSociale[$i][$x] += $DecaissementAdm[$i][$x+1];
            $this->VariationDettes[$i][$x+1] -= $DecaissementAdm[$i][$x+1];
            $this->DettesSociale[$i][$x] += $DecaissementPro[$i][$x+1];
            $this->DettesSociale[$i][$x] += $DecaissementCom[$i][$x+1];
            $this->DettesSociale[$i][$x] += $DecaissementRec[$i][$x+1];
            
        if( $x == 10){
            $this->DettesSociale[$i][11] += $DecaissementAdm[$i+1][0];
            $this->VariationDettes[$i+1][0] -= $this->DettesSociale[$i][11];
            $this->DettesSociale[$i][11] += $DecaissementPro[$i+1][0];
            $this->DettesSociale[$i][11] += $DecaissementCom[$i+1][0];
            $this->DettesSociale[$i][11] += $DecaissementRec[$i+1][0];
        }

        }
        }
      
        //----------------------------Fin---------------------------------------------------//
        //-------------------------End------------------------------------------------------//
      
        
        return $this->render('bfr/detail.html.twig', [
            'business' => $businessSession, 'tvacredit' => $this->tvacredit[$id], 'creancefiscale' => $this->creanceFiscales[$id]
            , 'DettesFiscales' =>  $this->DettesFiscales[$id] , 'tvaapayer' =>  $this->tvaadecaisser[$id], 'DettesSociale' => $this->DettesSociale[$id]
            ,'VariationDettes'=> $this->VariationDettes[$id],
            ]);
    }
}
