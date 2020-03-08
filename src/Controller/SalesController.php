<?php

namespace App\Controller;
use App\Entity\Businessplan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\SalesRepository;
use App\Repository\SalesdetailledRepository;
use App\Form\SalesdetailledFormType;
use App\Entity\Sales;
use App\Entity\Salesdetailled;
use Symfony\Component\HttpFoundation\Request;

/**
* @Route("/{_locale}/dashboard/my-business-plan/sales")
 */
class SalesController extends AbstractController
{
  private $var;
    /**
     * @Route("/", name="sales")
     */
    public function sales(ProductRepository $productRepository,SalesRepository $SalesRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Sale = new Sales();   
        $sales=new Salesdetailled();
        $listofsales =[] ;
        $listofCA=[];
        $businessSession =$this->container->get('session')->get('business');
        $sales = $entityManager->getRepository(Salesdetailled::class)->findBy(['sales'=> $businessSession->getSales()->getId()]);
        foreach($sales as  $key=>$value){
           
            $listofsale[$key] = $value->getdetailled();
        }
        
        foreach($listofsale as  $year=>$value){
            foreach($value as $productname=>$CA){
         
            $listofCA[$year][$productname] = array_sum($CA); 
             
           // array_push($listofCA,array_sum($CA));      
                   // dump(array_sum($CA));die();
                   // $i += $value[$j];     
        }
        }
      //  dump($listofCA);die();
        //$sales = $SalesRepository->findBybusinessplan($businessSession);
        
      /*
        foreach($sales as $sale){
        if($sale->getYear()==1){
            $CA = $sale->getSalesdetailled();
        }
           
        }*/
        
        
        $products=$productRepository->findBybusinessplan($businessSession);
        
        return $this->render('sales/index.html.twig',['listofCA'=> $listofCA ,
           'business' => $businessSession  ,  'products' => $products
        ]);
    }
    /**
     * @Route("/sales-year-{id}", name="salesyears")
     */
    public function salesyears(Request $request,ProductRepository $productRepository,$id,SalesRepository $SalesRepository,SalesdetailledRepository $SalesdetailledRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Sale = new Sales();   
        $sales=new Salesdetailled();
        $oldsales = new Salesdetailled();
        $listproducts= [];
        $listoftype = [];
        $businessSession =$this->container->get('session')->get('business');
        $oldsales = $businessSession->getSales();
        $year = $id ;
        $oldsales = $SalesdetailledRepository->findByYear($year+1);
        
        //$sales = $entityManager->getRepository(Salesdetailled::class)->findBy(['id'=> 11 , 'year' => 1 ]);
         
        //dump($sales[0]->getDetailled());die();
           
            $products=$productRepository->findBybusinessplan($businessSession);
        
            foreach($products as $product){
              
                $listproducts[$product->getName()] =$oldsales[0]->getDetailled()[$product->getName()];
                $listoftype[$product->getName()] = [$product->__toString()];
               
            
            //$listprice = $products[0]->getSellsprice()[0];
          //  $stripped = str_replace(' ', '', $listprice);
           
           // la boucle au ci dessus permet de creer une list qui contient comme cle le nom  de produit avec 12 case vide qui sont le nombre de mois
          
           $sales->setDetailled($listproducts);
            $sales->setYear($id+1);
           
           }

         
        
        
        
        dump($listoftype);die();
        
        //$sales->setCAtotal(['','','','','','','','','','','','']);
    
        $products=$productRepository->findBybusinessplan($businessSession);
        $form = $this->createForm(SalesdetailledFormType::class, $sales);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
             
        $sales=$form->getData();     
        
        //$entityManager->merge($sales);
        $entityManager->flush();

            return $this->redirectToRoute('sales');
        }
        return $this->render('sales/salesyears.html.twig',array(
            'business' => $businessSession  ,  'products' => $products , 'form' => $form->createView(), 
            'type' => $listoftype ,'sales' => $sales
        ));    
    
    }
     /**
     * @Route("/sale-year-{id}", name="saleyears")
     */
    public function test(Request $request, $id, ProductRepository $productRepository){
        $entityManager = $this->getDoctrine()->getManager();
        $Sale = new Sales();   
        $sales=new Salesdetailled();
        $businessSession =$this->container->get('session')->get('business');
        $years = $businessSession->getNumberofyears();
        $listoftype  =[];
        $listofprice =[];
        $listofreccuring= [];
        $listofname=[];
        $listofparametre=[];
        $products=$productRepository->findBybusinessplan($businessSession);
        $sales = $entityManager->getRepository(Salesdetailled::class)->findBy(['sales'=> $businessSession->getSales()->getId() , 'year' => $id+1 ]);
        foreach($products as $product){
            $listoftype[$product->getName()] = [$product->__toString()];
            array_push($listofname , $product->getName());
            if($product->__toString()=='Unit Invoicing'){
                $listofprice[$product->getName()] = [$product->getSellsprice()];
            }
            if($product->__toString()=='Reccuring Invoicing'){
                $listofreccuring[$product->getName()] = [$product->getSaleprice()];
                $listofparametre[$product->getName()] = [$product->getSaleprice(),$product->getPeriodicity(),$product->getFirstoccurence(),$product->getPermanent(),$product->getNumberofoccurences()];
            }   
        }
        //dump($listofparametre);die();
        $form = $this->createForm(SalesdetailledFormType::class, $sales[0]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $sales=$form->getData();     
            
            //$entityManager->merge($sales);
            $entityManager->flush();
    
            return $this->redirectToRoute('sales');
            }
        return $this->render('sales/test.html.twig' , ['form' => $form->createView(), 'listofprice' => $listofprice, 'id' => $id, 'listofreccuring' =>$listofreccuring,
         'business' => $businessSession ,'listofparametre' => $listofparametre,'products' => $products , 'sales' => $sales[0], 'type' => $listoftype , 'listofname' => $listofname ]);
    }

     /**
     * @Route("/salesreceipt", name="salesreceipt")
     */
    public function receipt(ProductRepository $productRepository){
      
        $businessSession =$this->container->get('session')->get('business');
        $products=$productRepository->findBybusinessplan($businessSession);
        
        return $this->render('sales/salesreceipt.html.twig' , ['business' => $businessSession ]) ;
    }
    private $arr =[];
         /**
     * @Route("/salesreceipt-year-{id}", name="salesreceiptyears")
     */
    public function receiptyear(SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,$id,SalesRepository $SalesRepository){
        
        $entityManager = $this->getDoctrine()->getManager();
        
        $businessSession =$this->container->get('session')->get('business');
        
        $years = $businessSession->getNumberofyears();
        $salesdetailled = $SalesdetailledRepository->findBy(['sales'=> $businessSession->getSales()->getId() /*, 'year' => $id+1 */]);
        
        $listofCA = [];
        //dump($salesdetailled);die();
        $loidencaissement = [] ;
        $TVAfinal0  =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'] ];
        $TVAfinal1  =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'] ];
        $TVAfinal2  =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'] ];
        $TVAfinal3  =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'] ];
        $TVAfinal4  =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'] ];


        $total = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
        $result0 = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
        $result1 = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
        $result2 = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
        $result3 = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
        $result4 = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
        $result = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];

        $final0=['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1'=> ['0','0','0','0','0','0','0','0','0','0','0','0']];
        $final1=['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1'=> ['0','0','0','0','0','0','0','0','0','0','0','0']];
        $final2=['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1'=> ['0','0','0','0','0','0','0','0','0','0','0','0']];
        $final3=['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1'=> ['0','0','0','0','0','0','0','0','0','0','0','0']];
        $final4=['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1'=> ['0','0','0','0','0','0','0','0','0','0','0','0']];

        $Cash = ['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'2' => ['300','300','300','300','300','300','300','300','300','300','300','300'],'3' => ['400','400','400','400','400','400','400','400','400','400','400','400']] ;
        $After30 =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'2' => ['300','300','300','300','300','300','300','300','300','300','300','300'],'3' => ['400','400','400','400','400','400','400','400','400','400','400','400']] ;
        $After60 =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'2' => ['300','300','300','300','300','300','300','300','300','300','300','300'],'3' => ['400','400','400','400','400','400','400','400','400','400','400','400']] ;
        $After90 =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'2' => ['300','300','300','300','300','300','300','300','300','300','300','300'],'3' => ['400','400','400','400','400','400','400','400','400','400','400','400']] ;
        $After120 =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'2' => ['300','300','300','300','300','300','300','300','300','300','300','300'],'3' => ['400','400','400','400','400','400','400','400','400','400','400','400']] ;
        $Sum = ['0' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'1' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'2' => ['0','0','0','0','0','0','0','0','0','0','0','0'],'3' => ['0','0','0','0','0','0','0','0','0','0','0','0']] ;
        $var = 0;
        $listofCA = $salesdetailled[0]->getDetailled(); // liste des CA  par année
        
       // dump($listofCA);die();
        $TVA = 0;
        $dontTVA=['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
        $startlist = [0,1,2,3,4];
        $TVAfinal=[];
        $products=$productRepository->findBybusinessplan($businessSession);
        
        foreach($products as $product){
            foreach ($listofCA as $key => $value) {
                if($key == $product->getName()){
                    if($product->__toString() == 'Unit Invoicing'){
                       $loidencaissement =  $product->getProductsRecieptRule(); // loi d'encaissement des vente 
                       //  dump($loidencaissement);die(); 
                       $TVA = $product->getvat();
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
                            ${'result0'}[$o] = floatval($result0[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                            ${'TVAfinal0'}[0][$o] = ${'TVAfinal0'}[0][$o] +  (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ; 
                        }
                          else if(($o-$c) ==1){
                            ${'result1'}[$o] = floatval($result1[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                            ${'TVAfinal1'}[0][$o] =  ${'TVAfinal1'}[0][$o] + (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ;
                           
                          }
                          else if(($o-$c) ==2){
                            ${'result2'}[$o] = floatval($result2[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                            ${'TVAfinal2'}[0][$o] =  ${'TVAfinal2'}[0][$o] + (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ;

                          }
                          else if(($o-$c) ==3){
                            ${'result3'}[$o] = floatval($result3[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                            ${'TVAfinal3'}[0][$o] =  ${'TVAfinal3'}[0][$o] + (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ;

                          }
                          else if(($o-$c) ==4){
                            ${'result4'}[$o] = floatval($result4[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                            ${'TVAfinal4'}[0][$o] =  ${'TVAfinal4'}[0][$o] + (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ;

                          }
                          $total[$o] = floatval($total[$o]) +(  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                            $dontTVA[$o] = (((($D*$TVA)/100)*$TVA)/100);
                            
                        }
                    
                       }
                       
                      //dump($result0,$result1,$result2,$result3,$result4,$dontTVA);
                      // die();
                      /* foreach($value as $chiffre){
                           $position = 0;
                        foreach($loidencaissement as $key => $LOI){
                           for($i=$position;$i<5+$position;$i+-+){
                            $result[$i] = $result[$i] + ((($LOI[$id]/100)*$chiffre) * ((($chiffre*$TVA)/100)+$chiffre));
                            
                        }
                        $position++;
                        dump($result); die(); 
                       }
                    }*/
                      
                    }
                    if($product->__toString() == 'Variable Invoicing'){
                      $loidencaissement =  $product->getProductreceipt();
                      $TVA = $product->getVat();
                      $pos=0;
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
                         ${'result0'}[$o] = floatval($result0[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                         ${'TVAfinal0'}[0][$o] = ${'TVAfinal0'}[0][$o] +  (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ; 
                     }
                       else if(($o-$c) ==1){
                         ${'result1'}[$o] = floatval($result1[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                         ${'TVAfinal1'}[0][$o] =  ${'TVAfinal1'}[0][$o] + (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ;
                        
                       }
                       else if(($o-$c) ==2){
                         ${'result2'}[$o] = floatval($result2[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                         ${'TVAfinal2'}[0][$o] =  ${'TVAfinal2'}[0][$o] + (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ;

                       }
                       else if(($o-$c) ==3){
                         ${'result3'}[$o] = floatval($result3[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                         ${'TVAfinal3'}[0][$o] =  ${'TVAfinal3'}[0][$o] + (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ;

                       }
                       else if(($o-$c) ==4){
                         ${'result4'}[$o] = floatval($result4[$o]) + (  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                         ${'TVAfinal4'}[0][$o] =  ${'TVAfinal4'}[0][$o] + (  (((($D*$TVA)/100))) * (((($loi0[$o-$c])/100)))  ) ;

                       }
                       $total[$o] = floatval($total[$o]) +(  (((($D*$TVA)/100)+$D)) * (((($loi0[$o-$c])/100)))  ) ;
                       $dontTVA[$o] = (((($D*$TVA)/100)*$TVA)/100);
                         
                     }
                 
                    }
                    




                    } 
                }
                
            }
             
        }
       
        //dump($TVAfinal0,$TVAfinal1);die();
        //cette boucle permet de couper les matrice resultat en matrice de 12 mois
        $p=0;
        
         for($i=0 ; $i<24  ; $i++){
          if($i>=0 && $i<12){
         $final0[$p][$i] = $final0[$p][$i] + $result0[$i]; // result0 c'est la matrice de la ligne Cash  
         $final1[$p][$i] = $final1[$p][$i] + $result1[$i]; // result1 c'est la matrice de la ligne After30
         $final2[$p][$i] = $final2[$p][$i] + $result2[$i];
         $final3[$p][$i] = $final3[$p][$i] + $result3[$i];
         $final4[$p][$i] = $final4[$p][$i] + $result4[$i];
         $TVAfinal[$p][$i] = $TVAfinal0[0][$i]+$TVAfinal1[0][$i] + $TVAfinal2[0][$i] + $TVAfinal3[0][$i] + $TVAfinal4[0][$i];
         $Sum[$p][$i]=$total[$i];
        }
        else {
          $p=1;
          //dump($i-12);
         // $final0[$p][$i-12]=1;
          //dump($final0);die();
          
          $final0[$p][$i-12] =$final0[$p][$i-12] + $result0[$i]; // result1 c'est la matrice de lign After30
          $final1[$p][$i-12] =$final1[$p][$i-12] + $result1[$i];
          $final2[$p][$i-12] =$final2[$p][$i-12] + $result2[$i];
          $final3[$p][$i-12] =$final3[$p][$i-12] + $result3[$i];
          $final4[$p][$i-12] =$final4[$p][$i-12] + $result4[$i];
         // $TVAfinal[$p][$i] = $TVAfinal0[0][$i]+$TVAfinal1[0][$i] + $TVAfinal2[0][$i] + $TVAfinal3[0][$i] + $TVAfinal4[0][$i];
          //$Sum[$p][$i]=$total[$i];
        }
        


        
      }
      //dump($final1);die();
        //dump($TVAfinal);die();
       // dump($final0,$final1,$final2,$final3,$final4);die();
          
         $Cash[0]=$final0[0];
         $Cash[1]=$final0[1];
         $After30[0]= $final1[0];
         $After30[1]= $final1[1];
         $After60[0]= $final2[0];
         $After60[1]= $final2[1];
         $After90[0]= $final3[0];
         $After90[1]= $final3[1];
         $After120[0]= $final4[0];
         $After120[1]= $final4[1];
         
       //  dump($arr);die();
         //dump($After30);die();
     //    dump($total);die();
        // dump($final0);die();
       /* $index = 0 ;
       foreach($loidencaissement as $key => $value){
          // dump($key,$value);
          //dump($key,$value[$id]);    
          $loi[$index] = $value[$id];
          for($i=0 ; $i<$index+1 ; $i++){
            ${"list" . $index}[$i]=0;
             
          }
          $y=0;
          for($i=$index+1 ; $i<12 ; $i++){
              
            //$result[$i]  =  floatval($value[$id])* 50;
            ${"list" . $index}[$i] =(floatval($value[$id])/100)*(floatval($listofCA['ASUS'][$y]) + ((floatval($listofCA['ASUS'][$i])*$TVA)/100));
              $y++;
        }
          $index = $index +1 ;
         
              
       }*/
     
      // die();
        
      // dump($listofCA);die();
       // dump($salesdetailled);die();
        return $this->render('sales/salesreceiptyear.html.twig',['id' => $id ,'total' => $Sum[0],"business" => $businessSession , "product" => $product , "Cash" => $Cash , "After30" => $After30 ,"After60" => $After60
        , "After90" => $After90 , "After120" => $After120,"TVA"=>$TVAfinal[0]]);
    }
   /**
     * @Route("/salesreceipt-years-{id}", name="salesreceiptyear")
     */
    public function receiptyears(SalesdetailledRepository $SalesdetailledRepository,ProductRepository $productRepository,$id,SalesRepository $SalesRepository){
      $entityManager = $this->getDoctrine()->getManager();
        
      $businessSession =$this->container->get('session')->get('business');
      
      $years = $businessSession->getNumberofyears();
      $salesdetailled = $SalesdetailledRepository->findBy(['sales'=> $businessSession->getSales()->getId() /*, 'year' => $id+1 */]);
      
      
      //dump($salesdetailled);die();
     // $TVAfinal0  =['0' => ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'] ];
      $loidencaissement = [] ;
      for($i=0 ; $i<$years ; $i++ ){
      
      ${'total'.$i} = ['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];
      ${'Cash'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;//Variable Cash pour afficher dans le tableau d'ancaissement
      ${'After30'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;// meme chose 
      ${'After60'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;
      ${'After90'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;
      ${'After120'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;
      
      ${'listofCA'.$i} = $salesdetailled[$i]->getDetailled();
      ${'SumofTVA'}[$i] = ['0','0','0','0','0','0','0','0','0','0','0','0'] ;
      for($s =0 ; $s < 5; $s++){
        
      }

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
  
        $TVA = 0;
        $dontTVA=['0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0'];   
        
        $products=$productRepository->findBybusinessplan($businessSession);

        foreach($products as $product){
          for($i=0 ; $i<$years ; $i++){
          foreach (${'listofCA'.$i} as $key => $value) {
              if($key == $product->getName()){
                  if($product->__toString() == 'Unit Invoicing'){
                     $loidencaissement =  $product->getProductsRecieptRule(); // loi d'encaissement des vente 
                     //  dump($loidencaissement);die(); 
                     $TVA = $product->getvat();
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
                    $loidencaissement =  $product->getProductreceipt(); // loi d'encaissement des vente 
                    //  dump($loidencaissement);die(); 
                    $TVA = $product->getvat();
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
              }
              
          }
        }
      
      }
     

//cette boucle permet de mettre les valeur qui depasse le 12 eme mois dans la nouvelle années
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
      $TVAfinal[$p][$i] = $TVAfinal0[$p][$i]+$TVAfinal1[$p][$i] + $TVAfinal2[$p][$i] + $TVAfinal3[$p][$i] + $TVAfinal4[$p][$i];
      $Sum[$p][$i]=$Sum[$p][$i] +${'total'.$p}[$i];
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
   //dump($Sum);die();
      return $this->render('sales/salesreceiptyear.html.twig',['id' => $id ,'total' => $Sum,"business" => $businessSession , "product" => $product , "Cash" => $Cash , "After30" => $After30 ,"After60" => $After60
      , "After90" => $After90 , "After120" => $After120,"TVA"=>$SumofTVA]);
    }
}
