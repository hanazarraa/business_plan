<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Businessplan;
use App\Entity\Product;
use App\Entity\Sales;
use App\Entity\Salesdetailled;
use App\Entity\UnitInovicing;
use App\Entity\ReccuringInvoicing;
use App\Entity\VariableInvoicing;
use App\Form\BusinessFormType;
use App\Form\ProductType;
use App\Form\UnitInvoicingType;
use App\Form\VariableInvoicingType;
use App\Form\ReccuringInvoicingType;
use App\Repository\ProductRepository;
use App\Repository\SalesRepository;
use App\Repository\BusinessplanRepository;
use App\Repository\SalesdetailledRepository;

/**
     * @Route("/{_locale}/dashboard/my-business-plan/products")
 */
class ProductsController extends AbstractController
{
    /**
     * @Route("/", name="products")
     */
    public function index(ProductRepository $productRepository){
       $businessSession =$this->container->get('session')->get('business');
       $products=$productRepository->findBybusinessplan($businessSession);
       if($products==null){
           return $this->render('products/products_index_vide.html.twig',['business' => $businessSession]);
       }else{
           return $this->render('products/products_index.html.twig',['products'=>$products,'business' => $businessSession]);
       }


    }
    /**
     * @Route("/choice_type_product", name="choice_type_product")
     */
    public function choiceType(){
        $businessSession =$this->container->get('session')->get('business');
        return $this->render('products/choice_type_product.html.twig',['business' => $businessSession]);
     
     }
      /**
     * @Route("/create_product", name="unit_invoicing_create")
     */
    public function unit_invoicing_create(Request $request,SalesRepository $SalesRepository,SalesdetailledRepository $SalesdetailledRepository){
        $unitinvoicing=new UnitInovicing();
        $Sale = new Sales();
        $Sales = new Sales();
        $SaleDetailled = new Salesdetailled();
        $SaleDetailled1 = new Salesdetailled();

        $businessSession =$this->container->get('session')->get('business');
        $Sale = $businessSession->getSales();
        $Sales = $SalesRepository->findByid($Sale->getId());
        $SaleDetailled1 = $SalesdetailledRepository->findBysales($Sales);
        $SaleDetailled->setSales($Sales[0]);
       // dump($SaleDetailled1);die();
        $numberofyears = $businessSession->getNumberofyears();
        $ListofSellsprice = [];
        for($i=0;$i<$numberofyears; $i++){
           
            for($y=0;$y<$numberofyears; $y++){
                array_push($ListofSellsprice, ''.$y);   
            } 
            ${'array' . ($i)} = $ListofSellsprice;
        }
        $unitinvoicing->setSellsprice($array0);
        $unitinvoicing->setProductsRecieptRule(['Cash'=> $array0,'After30'=>$array0,'After60'=>$array0,'After90'=>$array0,'After120'=>$array0]);
        $unitinvoicing->setProductCostSales($array0);
        $unitinvoicing->setPurchaseDisbursmentRule(['Cash'=> $array0,'After30'=>$array0,'After60'=>$array0,'After90'=>$array0,'After120'=>$array0]);
        $form = $this->createForm(UnitInvoicingType::class, $unitinvoicing);
        
        
          //$product->setName($request->request->get('name'));
          $form->handleRequest($request);
          
          if($form->isSubmitted() && $form->isValid()){
            
            $unitinvoicing=$form->getData();
             
             $unitinvoicing->setBusinessplan($businessSession);  
             //dump($SaleDetailled);die();
             $entityManager = $this->getDoctrine()->getManager();
             
              if($SaleDetailled1 ==[]){

              
                $SaleDetailled->setDetailled([$form->getData()->getName()=>['0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00']]);
                for($i = 1 ; $i<=$numberofyears; $i++){
                   $SaleDetailled->setYear($i);
                   $entityManager->merge($SaleDetailled);
                }
               } else {
                for($i = 1 ; $i<=$numberofyears; $i++){
                   
                    $sales = $entityManager->getRepository(Salesdetailled::class)->findBy(['year' => $i , 'sales' =>$SaleDetailled1[0]->getSales()->getId()  ]);
                    $list =  $sales[0]->getDetailled()  ;            
                    $list[$form->getData()->getName()] = ['0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00'] ;   
                    $sales[0]->setDetailled($list);
                    //$sales[0]->setDetailled([array_push($sales[0]->getDetailled(),$form->getData()->getName() => ['0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00']])); 
                    //dump($list);die();
                }
             }
             
             $entityManager->merge($unitinvoicing);
             $entityManager->flush();
 
              return $this->redirectToRoute('products');
              
          }
          return $this->render('products/unit_invoicing_create.html.twig',['business' => $businessSession,
              'form'=>$form->createView(),
          ]);
     
 
 
     }
     /**
     * @Route("/create_product_variable", name="Variable_invoicing")
     */
    public function variable_invoicing_create(Request $request,SalesRepository $SalesRepository,SalesdetailledRepository $SalesdetailledRepository){
        $unitinvoicing=new VariableInvoicing();
        $Sale = new Sales();
        $Sales = new Sales();
        $SaleDetailled = new Salesdetailled();
        $SaleDetailled1 = new Salesdetailled();

        $businessSession =$this->container->get('session')->get('business');
       
        $numberofyears = $businessSession->getNumberofyears();
        $Sale = $businessSession->getSales();
        $Sales = $SalesRepository->findByid($Sale->getId());
        $SaleDetailled1 = $SalesdetailledRepository->findBysales($Sales);
        $SaleDetailled->setSales($Sales[0]);
        $ListGenrated = [];
        // la boucle ci dessous permet de gerer ensemble de liste contient plusieure taille 
        // de notre matrice , exemple array0 => une liste contient des element de taille numberofyears
        // array1 => contient les element de array0 + les nouvelles elements (meme taille que la premiere)
        for($i=0;$i<$numberofyears; $i++){
           
        for($y=0;$y<$numberofyears; $y++){
            array_push($ListGenrated, ''.$y);   
        } 
        ${'array' . ($i)} = $ListGenrated;
    }
        // la ligne ci dessous permet d'instantier notre matrice on utilisant la liste obtenu dans la boucle
        // precedent ,  la premiere liste (array0) contient la taille necessaire de la matrice en question !
        $unitinvoicing->setProductreceipt(['Cash'=> $array0,'After30'=>$array0,'After60'=>$array0,'After90'=>$array0,'After120'=>$array0]);
        $unitinvoicing->setPurchasecostofsales($array0);
        $unitinvoicing->setPurchasedisbursement(['Cash'=> $array0,'After30'=>$array0,'After60'=>$array0,'After90'=>$array0,'After120'=>$array0]);
  
        
        $form = $this->createForm(VariableInvoicingType::class, $unitinvoicing);
     

        $form->handleRequest($request);
        $errorCollection = $form->getErrors();
      
        if($form->isSubmitted() && $form->isValid()){
             
            $unitinvoicing=$form->getData();
            $unitinvoicing->setBusinessplan($businessSession);     
           $entityManager = $this->getDoctrine()->getManager();


           if($SaleDetailled1 ==[]){

              
            $SaleDetailled->setDetailled([$form->getData()->getName()=>['0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00']]);
            for($i = 1 ; $i<=$numberofyears; $i++){
               $SaleDetailled->setYear($i);
               $entityManager->merge($SaleDetailled);
            }
           } else {
            for($i = 1 ; $i<=$numberofyears; $i++){
                
                $sales = $entityManager->getRepository(Salesdetailled::class)->findBy(['year' => $i ,'sales' =>$SaleDetailled1[0]->getSales()->getId() ]);
                
                $list =  $sales[0]->getDetailled()  ;            
                $list[$form->getData()->getName()] = ['0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00'] ;   
                $sales[0]->setDetailled($list);
                //$sales[0]->setDetailled([array_push($sales[0]->getDetailled(),$form->getData()->getName() => ['0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00']])); 
                //dump($list);die();
            }
         }
         
           $entityManager->merge($unitinvoicing);
           $entityManager->flush();

            return $this->redirectToRoute('Variable_invoicing');
            
        }
        return $this->render('products/variable_invoicing_create.html.twig',[ 'form'=>$form->createView(),'business' => $businessSession]);
    }

       /**
     * @Route("/create_product_reccuring", name="reccuring_invoicing_create")
     */
    public function reccuring_invoicing_create(Request $request){
        $reccuringinvoicing=new ReccuringInvoicing();
        $businessSession =$this->container->get('session')->get('business');
        $form = $this->createForm(ReccuringInvoicingType::class, $reccuringinvoicing);
        //$reccuringinvoicing->setBusinessplan($businessSession);
        $product = new Product();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
             
            $reccuringinvoicing=$form->getData();
           
           $reccuringinvoicing->setBusinessplan($businessSession);  
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->merge($reccuringinvoicing);
           $entityManager->flush();

            return $this->redirectToRoute('reccuring_invoicing_create');
            
        }
        return $this->render('products/reccuring_invoicing_create.html.twig',[ 'form'=>$form->createView(),'business' => $businessSession]);


    }

  /*  public function create(Request $request)
    {
        $business=new Businessplan();
        $form = $this->createForm(BusinessFormType::class, $business);

          $form->handleRequest($request);
          $user = $this->get('security.token_storage')->getToken()->getUser();
          
          if($form->isSubmitted() && $form->isValid()){
             
              $business=$form->getData();
            
             $business->setUser($user);
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($business);
             $entityManager->flush();
 
              return $this->redirectToRoute('dashboard');
              
          }
          return $this->render('create.html.twig',[
              'form'=>$form->createView(),
          ]);
     
    }*/
}
