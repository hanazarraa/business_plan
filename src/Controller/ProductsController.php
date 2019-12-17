<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Businessplan;
use App\Entity\Product;
use App\Entity\UnitInovicing;
use App\Form\BusinessFormType;
use App\Form\UnitInvoicingType;
use App\Form\UnitInvoicingTypeType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

/**
     * @Route("/products")
 */
class ProductsController extends AbstractController
{
    /**
     * @Route("/", name="products")
     */
    public function index(ProductRepository $productRepository){
        
       $products=$productRepository->findAll();
       if($products==null){
           return $this->render('products/products_index_vide.html.twig');
       }else{
           return $this->render('products/products_index.html.twig',array('products'=>$products));
       }


    }
    /**
     * @Route("/choice_type_product", name="choice_type_product")
     */
    public function choiceType(){
        return $this->render('products/choice_type_product.html.twig');
     
 
 
     }
      /**
     * @Route("/create_product", name="unit_invoicing_create")
     */
    public function unit_invoicing_create(Request $request){
       /* $product=new Product();
        
        $form = $this->createForm(UnitInvoicingType::class, $product);
        
        
   */
        $fields =json_decode( $request->getContent());
        
 
        $unit_invoicing = $this->createUnitInvoicing(new UnitInovicing(), $fields);
 
        $this->productRepository->insert($unit_invoicing);
       
 
        return new Response(null, Response::HTTP_CREATED);
    }
    private function createUnitInvoicing(Product $product, array $fields): Product
    {
        $product->setName($fields['name']);
       
        return $product;
    }
        
          /*if($form->isSubmitted() && $form->isValid()){
        
              $product=$form->getData();
            
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($product);
             $entityManager->flush();
 
              return $this->redirectToRoute('products');
              
          }
          return $this->render('products/unit_invoicing_create.html.twig',[
              'form'=>$form->createView(),
          ]);
     
 
 
     }*/
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
