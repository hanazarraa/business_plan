<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\SalesdetailledRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use App\Repository\SalesRepository;
use App\Controller\SalesController;
/**
* @Route("/{_locale}/dashboard/my-business-plan/purchase")
 */
class PurchaseController extends AbstractController
{
   private $fianlCA;
    private $Sum;
    private  $Somme; 
    private  $total;
    private $businessSession;
    private $products;
    private $list;
    public function __construct(\Knp\Snappy\Pdf $knpSnappy) {
        
         $this->knpSnappy = $knpSnappy; }
   
    /**
     * @Route("/", name="purchase")
     */
    public function index(SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,Request $request,SalesRepository $SalesRepository)
    {
        $this->businessSession =$this->container->get('session')->get('business');
        $this->products=$productRepository->findBybusinessplan($this->businessSession);
        $id=0;
        $this->detail($SalesdetailledRepository,$productRepository,$id,$request,$SalesRepository);
       
     
        //dump($SalesController->sales($request,$productRepository,$SalesRepository));die();
        return $this->render('purchase/index.html.twig', [
            'business' => $this->businessSession ,'products' => $this->products , 'Somme' => $this->Somme ,'total'=> $this->total 
        ]);
    }
        /**
     * @Route("/purchasedetailled-{id}", name="purchasedet")
     */
    public function detail(SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,$id,Request $request,SalesRepository $SalesRepository)
    {
        $this->businessSession =$this->container->get('session')->get('business');
        $this->products=$productRepository->findBybusinessplan($this->businessSession);
        $years = $this->businessSession->getNumberofyears();
        $salesdetailled = $SalesdetailledRepository->findBy(['sales'=> $this->businessSession->getSales()->getId()]);
        $response = $this->forward('App\Controller\SalesController::sales', [
          'request'  => $request,
          'productRepository' => $productRepository,
          'SalesRepository' => $SalesRepository,
      ]);
      $finalCA = SalesController::getfinalca();
      
        // initialisation de liste 
        //---------------------------------------------------
        //$this->list =["Nodata0"=> ['0']];
        
        //---------------------------------------------------
        $TVA = 0;
        for($i=0 ; $i<$years ; $i++ ){
            ${'listofCA'.$i} = $salesdetailled[$i]->getDetailled();
            $this->Sum[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'];
            $this->total[$i] =  0;
        }
        foreach($this->products as $product){
            if($product->__toString() == 'Unit Invoicing'){
                
            $Costofsales = $product->getProductCostSales();
            for($i=0 ; $i<$years ; $i++){
                foreach (${'listofCA'.$i} as $key => $value){
                    if($key == $product->getName()){
                   for($t=0 ;$t<12 ;$t++){
                       ${''.$key}[$i][$t] = (($value[$t] * $Costofsales[$i])/100);
                       ${''.$key.$i} =  ${''.$key}[$i];
                       $this->list[''.$key.$i] =  ${''.$key.$i};
                       $this->Sum[$i][$t] = $this->Sum[$i][$t] + ${''.$key}[$i][$t] ; 
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
                   $this->list[''.$key.$i] =  ${''.$key.$i};
                   $this->Sum[$i][$t] = $this->Sum[$i][$t] + ${''.$key}[$i][$t] ;
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
          foreach ($finalCA as $key => $value){
              if($key == $product->getName()){
             for($t=0 ;$t<12 ;$t++){
             // dump($Costofsales);die();
                 
                 ${''.$key}[$i][$t] = (($value[$i][$t] * $Costofsales)/100);
                 ${''.$key.$i} =  ${''.$key}[$i];
                 $this->list[''.$key.$i] =  ${''.$key.$i};
                 $this->Sum[$i][$t] = $this->Sum[$i][$t] + ${''.$key}[$i][$t] ;
                // $list[${''.$key.$i}]=${''.$key.$i};
             }
          }
              //$list[$product.getName().''.$i] = 
    
  }
  }
      }


    }
     //dump($this->list);die();
    $index = 0 ;
    if($this->list!=null){
        foreach ($this->list as $key => $value){
          
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
  }
    //dump($this->Somme,$total);die();
    //$this->knpSnappy->generate('http://127.0.0.1:8000/fr/dashboard/my-business-plan/purchase/purchasedetailled-0', 'C:/Users/ahmed/XXX.pdf');
       //dump($this->Somme); die();
        return $this->render('purchase/receiptdetaill.html.twig', [ 'Somme' => $this->Sum ,
            'business' => $this->businessSession ,'products' => $this->products   , 'list' => $this->list, 'id'=> $id
        ]);
    
    
}
 /**
     * @Route("/purchasedetailled-{id}/pdf", name="purchasedet-pdf")
     */
public function toPDF(SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,$id,Request $request){
    $this->detail($SalesdetailledRepository,$productRepository,$id);
    $html = $this->renderView('purchase/modelpdf2.html.twig',[
        'base_dir' => $this->getParameter('kernel.project_dir') . '/../businessplan/public/' . $request->getBasePath(),
        'Somme' => $this->Sum ,
            'business' => $this->businessSession ,'products' => $this->products   , 'list' => $this->list, 'id'=> $id
    ]); // use absolute path!
   
        return new PdfResponse(
            $this->knpSnappy->getOutputFromHtml($html,[ 'encoding' => 'utf-8','orientation' => 'landscape' ]),
            'file.pdf'
        );
}
 /**
     * @Route("/pdf", name="purchase-pdf")
     */
    public function PDF(SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,Request $request){
        $this->index($SalesdetailledRepository,$productRepository);
        $html = $this->renderView('purchase/modelpdf.html.twig',[
            'base_dir' => $this->getParameter('kernel.project_dir') . '/../businessplan/public/' . $request->getBasePath(),
            'business' => $this->businessSession ,'products' => $this->products , 'Somme' => $this->Somme ,'total'=> $this->total 

        ]); // use absolute path!
   
        return new PdfResponse(
            $this->knpSnappy->getOutputFromHtml($html,[ 'encoding' => 'utf-8','orientation' => 'landscape']),
            'file.pdf'
        );
    }
            /**
     * @Route("/purchasedisbursement-{id}", name="purchasedisbursement")
     */
    public function disbursement(SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,$id,Request $request,SalesRepository $SalesRepository){
        $this->businessSession =$this->container->get('session')->get('business');
        $this->products=$productRepository->findBybusinessplan($this->businessSession);
        $this->detail($SalesdetailledRepository,$productRepository,$id,$request,$SalesRepository);
        $years = $this->businessSession->getNumberofyears();
        $prof ;
        foreach($this->list as $key => $listofpurchase){
        
        $prof[substr($key,0,strlen($key)-1)][substr($key,strlen($key)-1,strlen($key))]= $listofpurchase ;
        
    }
   
    for($i=0 ; $i<$years ; $i++ ){
    foreach($prof as $key => $listofPur){
      
         ${'listOfPu'.$i}[$key] =$listofPur[$i];  
    }}
    //dump($listOfPu0);die();
   // dump($listOfPu0,$listOfPu1,$listOfPu2,$listOfPu3);die();
    for($i=0 ; $i<$years ; $i++ ){
      
        ${'total'.$i} = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
        ${'Cash'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;//Variable Cash pour afficher dans le tableau d'ancaissement
        ${'After30'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;// meme chose 
        ${'After60'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;
        ${'After90'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;
        ${'After120'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;
        ${'Revenue'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;
        
        $TVAreccurente[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;
        //${'listofCA'.$i} = $salesdetailled[$i]->getDetailled();
        ${'SumofTVA'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;

  
      }
      for($t=0 ; $t<$years+1 ; $t++ ){
        for($s =0 ; $s < 5; $s++){
          $Sum[$t] =  ['0','0','0','0','0','0','0','0','0','0','0','0'];//variable pour calculer la somme d'encaissment pour chaque colonne
          ${'final'.$s}[$t] =['0','0','0','0','0','0','0','0','0','0','0','0'];//variable pour calculer la valeur finale de valeur encaisser
          ${'result'.$s.'-'.$t}= ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];//cette variable pour calculer la valeur encaisser suivant la loi d'encaissement $s les ligne(Cash,After30...) $t années
          ${'TVAfinal'.$s}[$t] = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'] ;// TVAfinal calculer le tva pour chaquer valeur encaisser 
          ${'TVAfinal'}[$t] = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'] ;// cette variavle va stocker la somme de TVAfinal precedent pour afficher dans la ligne DontTVA
        }
      }
      $dontTVA=['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];   
 $TVA=0;
      foreach($this->products as $product){
        for($i=0 ; $i<$years ; $i++){
        foreach (${'listOfPu'.$i} as $key => $value) {
            if($key == $product->getName()){
                if($product->__toString() == 'Unit Invoicing'){
                   $loidencaissement =  $product->getPurchaseDisbursmentRule(); // loi d'encaissement des vente 
                   //  dump($loidencaissement);die(); 
                   $TVA = $product->getVatPurchases();
                   $pos=0;
                   //cette boucle permet de transformer la matrice de loi d'encaissement d'une axe y(par clé) vers axe x(par années)  
                   foreach($loidencaissement as $V=>$u){
                       for($x=0  ;$x<$years;$x++){
                      ${'loi'.$x}[$pos]= $u[$x];}
                      $pos++;     
                   }
                 
                   foreach($value as $c=>$D){
                     //${'result'.$s}[$o]=($value[$o]*$TVA/100)+$value[$o];
                    //cette boucle va multiplier la valeur de liste de CA par la loi et met chacune dans la bonne place exemple CA = 100 , la loi 50%(After30) et 50%(After60) 
                    //donc (100 *50%)  dans la place After 30 ($result1)  et  100 *50 % After60($result2) et ajouter le TVA 
                    for($o = $c ; $o<5+$c ; $o++){
                      //  ${'result'}[$o] =${'result'}[$o]+ 120 ;
                       //
                      if(($o-$c) ==0){
                        ${'result0-'.$i}[$o] = floatval(${'result0-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                        ${'TVAfinal0'}[$i][$o] = ${'TVAfinal0'}[$i][$o] +  (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ; 
                        
                      }
                      else if(($o-$c) ==1){
                        ${'result1-'.$i}[$o] = floatval(${'result1-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                        ${'TVAfinal1'}[$i][$o] =  ${'TVAfinal1'}[$i][$o] + (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                        
                      }
                      else if(($o-$c) ==2){
                        ${'result2-'.$i}[$o] = floatval(${'result2-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                        ${'TVAfinal2'}[$i][$o] =  ${'TVAfinal2'}[$i][$o] + (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                      
                      }
                      else if(($o-$c) ==3){
                        ${'result3-'.$i}[$o] = floatval(${'result3-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                        ${'TVAfinal3'}[$i][$o] =  ${'TVAfinal3'}[$i][$o] + (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                        
                      }
                      else if(($o-$c) ==4){
                        ${'result4-'.$i}[$o] = floatval(${'result4-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                        ${'TVAfinal4'}[$i][$o] =  ${'TVAfinal4'}[$i][$o] + (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                       
                      }
                      ${'total'.$i}[$o] = floatval(${'total'.$i}[$o]) +(  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                        $dontTVA[$o] = (((($D*$TVA)/100)*$TVA)/100);
                        
                    }
                
                   }
                   
          
                  
                }
                if($product->__toString() == 'Variable Invoicing'){
                  $loidencaissement =  $product->getPurchasedisbursement(); // loi d'encaissement des vente 
                  //  dump($loidencaissement);die(); 
                  $TVA = $product->getVatonpurchase();
                  $pos=0;
                  //cette boucle permet de transformer la matrice de loi d'encaissement d'une axe y(par clé) vers axe x(par années)  
                  foreach($loidencaissement as $key=>$u){
                      for($x=0  ;$x<$years;$x++){
                     ${'loi'.$x}[$pos]= $u[$x];}
                     $pos++;     
                  }
                  
                  foreach($value as $c=>$D){
                    //${'result'.$s}[$o]=($value[$o]*$TVA/100)+$value[$o];
                   
                   for($o = $c ; $o<5+$c ; $o++){
                     //  ${'result'}[$o] =${'result'}[$o]+ 120 ;
                      //
                     if(($o-$c) ==0){
                       ${'result0-'.$i}[$o] = floatval(${'result0-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                       ${'TVAfinal0'}[$i][$o] = ${'TVAfinal0'}[$i][$o] +  (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ; 
                   }
                     else if(($o-$c) ==1){
                       ${'result1-'.$i}[$o] = floatval(${'result1-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                       ${'TVAfinal1'}[$i][$o] =  ${'TVAfinal1'}[$i][$o] + (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                      
                     }
                     else if(($o-$c) ==2){
                       ${'result2-'.$i}[$o] = floatval(${'result2-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                       ${'TVAfinal2'}[$i][$o] =  ${'TVAfinal2'}[$i][$o] + (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;

                     }
                     else if(($o-$c) ==3){
                       ${'result3-'.$i}[$o] = floatval(${'result3-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                       ${'TVAfinal3'}[$i][$o] =  ${'TVAfinal3'}[$i][$o] + (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;

                     }
                     else if(($o-$c) ==4){
                       ${'result4-'.$i}[$o] = floatval(${'result4-'.$i}[$o]) + (  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                       ${'TVAfinal4'}[$i][$o] =  ${'TVAfinal4'}[$i][$o] + (  (((($D*$TVA)/100))) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;

                     }
                     ${'total'.$i}[$o] = floatval(${'total'.$i}[$o]) +(  (((($D*$TVA)/100)+$D)) * ((((${'loi'.$i}[$o-$c])/100)))  ) ;
                       ${'dontTVA'.$i}[$o] = (((($D*$TVA)/100)*$TVA)/100);
                       
                   }
               
                  }
                




                } 
                if($product->__toString() == 'Reccuring Invoicing'){
                  $TVA = $product->getVatonpurchases();
                  for($p=0;$p<12;$p++){
                  $Revenue[$i][$p] = $Revenue[$i][$p] + $value[$p] + (($value[$p] *$TVA)/100);
                  $TVAreccurente[$i][$p] = $TVAreccurente[$i][$p] + (($value[$p] *$TVA)/100);
                }
                
                }
            }
            
        }
      }
    
    }
    //dump($TVAreccurente);die();
    //-------------------------------------------------------------------------------
    $p=0;
      $M = 11*$years;
      for($p=0 ; $p<$years  ; $p++){
      for($i=0 ; $i<20  ; $i++){
       if($i>=0 && $i<12){
      $final0[$p][$i] = $final0[$p][$i] + ${'result0-'.$p}[$i]; // result0 c'est la matrice de la ligne Cash  
      $final1[$p][$i] = $final1[$p][$i] + ${'result1-'.$p}[$i]; // result1 c'est la matrice de la ligne After30
      $final2[$p][$i] = $final2[$p][$i] + ${'result2-'.$p}[$i];
      $final3[$p][$i] = $final3[$p][$i] + ${'result3-'.$p}[$i];
      $final4[$p][$i] = $final4[$p][$i] + ${'result4-'.$p}[$i];
      $TVAfinal[$p][$i] = $TVAfinal0[$p][$i]+$TVAfinal1[$p][$i] + $TVAfinal2[$p][$i] + $TVAfinal3[$p][$i] + $TVAfinal4[$p][$i] + $TVAreccurente[$p][$i];
      $Sum[$p][$i]=$Sum[$p][$i] +${'total'.$p}[$i]  +$Revenue[$p][$i];
     }
     else {
       
       //dump($i-12);
      // $final0[$p][$i-12]=1;
       //dump($final0);die();
       
       $final0[$p+1][$i-12] =$final0[$p+1][$i-12] + ${'result0-'.$p}[$i]; // result1 c'est la matrice de lign After30
       $final1[$p+1][$i-12] =$final1[$p+1][$i-12] + ${'result1-'.$p}[$i];
       $final2[$p+1][$i-12] =$final2[$p+1][$i-12] + ${'result2-'.$p}[$i];
       $final3[$p+1][$i-12] =$final3[$p+1][$i-12] + ${'result3-'.$p}[$i];
       $final4[$p+1][$i-12] =$final4[$p+1][$i-12] + ${'result4-'.$p}[$i];
       $TVAfinal0[$p+1][$i-12]=$TVAfinal0[$p+1][$i-12] + $TVAfinal0[$p][$i];
       $TVAfinal1[$p+1][$i-12]=$TVAfinal1[$p+1][$i-12] + $TVAfinal1[$p][$i];
       $TVAfinal2[$p+1][$i-12]=$TVAfinal2[$p+1][$i-12] + $TVAfinal2[$p][$i];
       $TVAfinal3[$p+1][$i-12]=$TVAfinal3[$p+1][$i-12] + $TVAfinal3[$p][$i];
       $TVAfinal4[$p+1][$i-12]=$TVAfinal4[$p+1][$i-12] + $TVAfinal4[$p][$i];
      $Sum[$p+1][$i-12]=$Sum[$p+1][$i-12] + ${'total'.$p}[$i];
      
      $TVAfinal[$p+1][$i-12] = $TVAfinal0[$p][$i]+$TVAfinal1[$p][$i] + $TVAfinal2[$p][$i] + $TVAfinal3[$p][$i] + $TVAfinal4[$p][$i];

     }
    
    }

   }
 //dump($final2,$final3,$final4);die();
   for($q=0 ; $q<$years  ; $q++){
   for($r=0 ; $r<12  ; $r++){
    $SumofTVA[$q][$r] = $TVAfinal[$q][$r];
   }
  }
  //finalement on mettre toute les resultat dans un tableau final pour l'afficher
  //dump($SumofTVA);die();
   for($q=0 ; $q<$years  ; $q++){
   $Cash[$q]=$final0[$q];
   $After30[$q]= $final1[$q];
   $After60[$q]= $final2[$q];
   $After90[$q]= $final3[$q];
   $After120[$q]= $final4[$q];
   
   }
   //dump($SumofTVA);die();
               return $this->render('purchase/purchasedisbursment.html.twig',[
           'id' => $id , 'business' => $this->businessSession , 'products' => $this->products ,"Cash" => $Cash , "After30" => $After30 ,"After60" => $After60
           ,'total' => $Sum , "After90" => $After90 , "After120" => $After120,"TVA"=>$SumofTVA  ,'revenue' => $Revenue
        ]);

    }
}