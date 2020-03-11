<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\SalesdetailledRepository;
/**
* @Route("/{_locale}/dashboard/my-business-plan/purchase")
 */
class PurchaseController extends AbstractController
{
    private  $Somme; 
    private  $total;
    /**
     * @Route("/", name="purchase")
     */
    public function index(SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository)
    {
        $businessSession =$this->container->get('session')->get('business');
        $products=$productRepository->findBybusinessplan($businessSession);
        $id=0;
        $this->detail($SalesdetailledRepository,$productRepository,$id);
        //dump($this->Somme);die();
        return $this->render('purchase/index.html.twig', [
            'business' => $businessSession ,'products' => $products , 'Somme' => $this->Somme ,'total'=> $this->total 
        ]);
    }
        /**
     * @Route("/purchasedetailled-{id}", name="purchasedet")
     */
    public function detail(SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,$id)
    {
        $businessSession =$this->container->get('session')->get('business');
        $products=$productRepository->findBybusinessplan($businessSession);
        $years = $businessSession->getNumberofyears();
        $salesdetailled = $SalesdetailledRepository->findBy(['sales'=> $businessSession->getSales()->getId()]);
        
        // initialisation de liste 
        //---------------------------------------------------
       
        $list = [];
        //---------------------------------------------------
        $TVA = 0;
        for($i=0 ; $i<$years ; $i++ ){
            ${'listofCA'.$i} = $salesdetailled[$i]->getDetailled();
            $Sum[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'];
            $this->total[$i] =  0;
        }
        foreach($products as $product){
            if($product->__toString() == 'Unit Invoicing'){
                
            $Costofsales = $product->getProductCostSales();
            for($i=0 ; $i<$years ; $i++){
                foreach (${'listofCA'.$i} as $key => $value){
                    if($key == $product->getName()){
                   for($t=0 ;$t<12 ;$t++){
                       ${''.$key}[$i][$t] = (($value[$t] * $Costofsales[$i])/100);
                       ${''.$key.$i} =  ${''.$key}[$i];
                       $list[''.$key.$i] =  ${''.$key.$i};
                       $Sum[$i][$t] = $Sum[$i][$t] + ${''.$key}[$i][$t] ; 
                   }
                    }
                    //$list[$product.getName().''.$i] = 
          
        }
     
        }
        
        }
        if($product->__toString()=='Variable Invoicing'){
          $Costofsales = $product->getPurchasecostofsales();
          for($i=0 ; $i<$years ; $i++){
            foreach (${'listofCA'.$i} as $key => $value){
                if($key == $product->getName()){
               for($t=0 ;$t<12 ;$t++){
                  // dump($value[$t]);die();
                   ${''.$key}[$i][$t] = (($value[$t] * $Costofsales[$i])/100);
                   ${''.$key.$i} =  ${''.$key}[$i];
                   $list[''.$key.$i] =  ${''.$key.$i};
                   $Sum[$i][$t] = $Sum[$i][$t] + ${''.$key}[$i][$t] ;
                  // $list[${''.$key.$i}]=${''.$key.$i};
               }
            }
                //$list[$product.getName().''.$i] = 
      
    }
    }
        }
       // dump(${''.$key.$i});die();
  
       if($product->__toString()=='Reccuring Invoicing'){
        $Costofsales = $product->getPurchasecostofsales();
        for($i=0 ; $i<$years ; $i++){
          foreach (${'listofCA'.$i} as $key => $value){
              if($key == $product->getName()){
             for($t=0 ;$t<12 ;$t++){
                // dump($value[$t]);die();
                 ${''.$key}[$i][$t] = (($value[$t] * $Costofsales[$i])/100);
                 ${''.$key.$i} =  ${''.$key}[$i];
                 $list[''.$key.$i] =  ${''.$key.$i};
                 $Sum[$i][$t] = $Sum[$i][$t] + ${''.$key}[$i][$t] ;
                // $list[${''.$key.$i}]=${''.$key.$i};
             }
          }
              //$list[$product.getName().''.$i] = 
    
  }
  }
      }


    }
    $index = 0 ;
    
        foreach ($list as $key => $value){
          
     $this->Somme[substr($key,0,strlen($key)-1)][$index] = array_sum($value);
     $index++;
     if($index==$years){
     $index = 0 ;
     }
    
}

   for($x = 0 ;$x<$years;$x++){
       foreach($this->Somme as $key => $value){
      // dump($key,$value);
      $this->total[$x] = floatval($this->total[$x]) +  floatval($value[$x]);
       
    }}
    //dump($this->Somme,$total);die();
    
       //dump($this->Somme); die();
        return $this->render('purchase/receiptdetaill.html.twig', [ 'Somme' => $Sum ,
            'business' => $businessSession ,'products' => $products   , 'list' => $list, 'id'=> $id
        ]);
    
    
}
}