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
        $this->totalwithname = SalesController::getTotalwithname();
        $finalCA = SalesController::getfinalca(); 

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
          

        //-------------------------End------------------------------------------------------//
        
  //dump($finalCA);die();
        return $this->render('bfr/detail.html.twig', [
            'business' => $businessSession,
        ]);
    }
}
