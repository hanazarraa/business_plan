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
    /**
     * @Route("/", name="sales")
     */
    public function sales(ProductRepository $productRepository,SalesRepository $SalesRepository)
    {
        $sales=new Sales();
        
        $businessSession =$this->container->get('session')->get('business');
       /* $sales = $SalesRepository->findBybusinessplan($businessSession);
      
        foreach($sales as $sale){
        if($sale->getYear()==1){
            $CA = $sale->getSalesdetailled();
        }
           
        }*/
        
        
     
        $products=$productRepository->findBybusinessplan($businessSession);
        return $this->render('sales/index.html.twig',[
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
        $listoftype  =[];
        $products=$productRepository->findBybusinessplan($businessSession);
        $sales = $entityManager->getRepository(Salesdetailled::class)->findBy(['sales'=> $businessSession->getSales()->getId() , 'year' => $id+1 ]);
        foreach($products as $product){
            $listoftype[$product->getName()] = [$product->__toString()];
        }
        
        $form = $this->createForm(SalesdetailledFormType::class, $sales[0]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
             
            $sales=$form->getData();     
            
            //$entityManager->merge($sales);
            $entityManager->flush();
    
            return $this->redirectToRoute('sales');
            }
        return $this->render('sales/test.html.twig' , ['form' => $form->createView(),
         'business' => $businessSession ,'products' => $products , 'sales' => $sales[0], 'type' => $listoftype ]);
    }
}
