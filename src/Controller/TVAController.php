<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
class TVAController extends AbstractController
{
    /**
     * @Route("/{_locale}/dashboard/my-business-plan/tva-year-{id}", name="tva")
     */
    public function index($id,Request $request,ProductRepository $productRepository)
    {
        $businessSession =$this->container->get('session')->get('business');
        $products=$productRepository->findBybusinessplan($businessSession);
        $response = $this->forward('App\Controller\SalesController::test', [
            'request'  => $request,
            'id' => $id,
            'ProductRepository' => $productRepository,
                
        ]);
        $saleslist = SalesController::getlist();
        
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
       
        return $this->render('tva/index.html.twig', [
            'business' => $businessSession
        ]);
    }
}
