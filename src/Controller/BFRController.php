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
    private $totalwithname ;
    /**
     * @Route("/", name="BFR")
     */
    public function index(Request $request,SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,SalesRepository $SalesRepository)
    {
        $id = 1 ;
        $businessSession =$this->container->get('session')->get('business');
        $response = $this->forward('App\Controller\SalesController::receiptyears', [
            'Request' => $request,
            'SalesdetailledRepository'  => $SalesdetailledRepository,
            'ProductRepository' => $productRepository,
            'id' => $id ,
            'SalesRepository' => $SalesRepository,
        ]);  
        $this->totalwithname = SalesController::getTotalwithname();
        
       
        return $this->render('bfr/index.html.twig', [
            'business' => $businessSession,
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
        $creanceFiscales[$i][$x] = "0.00";
        $DettesFiscales[$i][$x] = "0.00";
        $variationcreances[$i][$x] = "0.00";
        $DettesSociale[$i][$x] = "0.00";
         
            }
        }   
        for($i=0;$i<$years + 1;$i++){
            for($x=0;$x<12;$x++){
                $VariationDettes[$i][$x] = "0.00";     }}
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
        $tvacredit = TVAController::getcreditTVA();
        $tvaadecaisser =  TVAController::getTvadecaisser();
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
            $creanceFiscales[$i][$x] = abs($isdufin[$i][$x]);// metter  en valeur absolue
            }
            else {
            $DettesFiscales[$i][$x]  = $isdufin[$i][$x] ;
            }
        }
        }
       
        //----------------------------Fin---------------------------------------------------//
        //----------------------------Dettes Sociale ---------------------------------------//
        for($i=0;$i<$years;$i++){
          for($x=0;$x<11;$x++){
            
            $DettesSociale[$i][$x] += $DecaissementAdm[$i][$x+1];
            $VariationDettes[$i][$x+1] -= $DecaissementAdm[$i][$x+1];
            $DettesSociale[$i][$x] += $DecaissementPro[$i][$x+1];
            $DettesSociale[$i][$x] += $DecaissementCom[$i][$x+1];
            $DettesSociale[$i][$x] += $DecaissementRec[$i][$x+1];
            
        if( $x == 10){
            $DettesSociale[$i][11] += $DecaissementAdm[$i+1][0];
            $VariationDettes[$i+1][0] -= $DettesSociale[$i][11];
            $DettesSociale[$i][11] += $DecaissementPro[$i+1][0];
            $DettesSociale[$i][11] += $DecaissementCom[$i+1][0];
            $DettesSociale[$i][11] += $DecaissementRec[$i+1][0];
        }

        }
        }
      
        //----------------------------Fin---------------------------------------------------//
        //-------------------------End------------------------------------------------------//
      
        
        return $this->render('bfr/detail.html.twig', [
            'business' => $businessSession, 'tvacredit' => $tvacredit[$id], 'creancefiscale' => $creanceFiscales[$id]
            , 'DettesFiscales' =>  $DettesFiscales[$id] , 'tvaapayer' =>  $tvacredit[$id], 'DettesSociale' => $DettesSociale[$id]
            ,'VariationDettes'=> $VariationDettes[$id],
            ]);
    }
}
