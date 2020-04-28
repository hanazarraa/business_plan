<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Businessplan;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Sales;
use App\Entity\Salesdetailled;
use App\Form\BusinessFormType;
use App\Entity\Generalexpenses;
use App\Entity\Generalexpensesdetail;
class BusinessController extends AbstractController
{
    
    /**
     * @Route("/{_locale}/dashboard/creer-business-plan", name="businessplan_create")
     */
    public function create(Request $request)
    {
        $business=new Businessplan();
        $sales= new Sales();
        $expenses = new Generalexpenses();
        $expenssesdetail = new Generalexpensesdetail();
        $form = $this->createForm(BusinessFormType::class, $business);
        $listdefrais =["eau","autre","loyers","assurance","honorairec","honorairej","impotettax","proprietes","deplacement","posteettelecom","fournitureentretient","fournitureadmenstrative"];
        $listProduction =["bail","petiteq","emballage","delpacement","autre"];
        $listdetail =["bail","petiteq","emballage","deplacement","autre"];
        $listCommercial =["publicite","evenmentiel","conseils","documentation","promotions","salons","deplacementsttc","autre"];
        $listRecherche = ["petiteq","conseils","congres","deplacement","autre"];  
        $form->handleRequest($request);
          $user = $this->get('security.token_storage')->getToken()->getUser();
          
          if($form->isSubmitted() && $form->isValid()){
   
             $business=$form->getData();
             $business->setUser($user);
             $business->setSales($sales);
             $business->setCode("".strtoupper(bin2hex(openssl_random_pseudo_bytes(32))));
             $business->setRangeofdetail(1);
             
             //$newgeneralexpensses->setAdministration($admisinstrator);
             $entityManager = $this->getDoctrine()->getManager();
             $years = $form->getData()->getNumberofyears();
             $entityManager->persist($business);
             //----------------------------Frais generaux-----------------//
             $expenses->setBusinessplan($business);
             foreach($listdefrais as $value){
             for($i=0;$i<($years-1);$i++){
             $admisinstrator[$value][$i] = "0.00";}}  //list pour les frais generaux global
             foreach($listProduction as $value){
             for($i=0;$i<($years-1);$i++){
             $production[$value][$i] = "0.00";}}
             foreach($listCommercial as $value){
             for($i=0;$i<($years-1);$i++){
             $commercial[$value][$i] = "0.00";}}
             foreach($listRecherche as $value){
             for($i=0;$i<($years-1);$i++){
             $recherche[$value][$i] = "0.00";}}
             $expenses->setAdministration($admisinstrator);
             $expenses->setProduction($production);
             $expenses->setSales($commercial);
             $expenses->setResearch($recherche);          
             $entityManager->persist($expenses);
            foreach($listdefrais as $value){
                for($i=0;$i<12;$i++){
                $detaillist[$value][$i] = "0.00";     // list pour les frais generaux detailler
                }}
            foreach($listdetail as $value){
                    for($i=0;$i<12;$i++){
                    $productiondetail[$value][$i] = "0.00";   
                }}
            foreach($listCommercial as $value){
                    for($i=0;$i<12;$i++){
                    $commercialdetail[$value][$i] = "0.00";   
                }}
            foreach($listRecherche as $value){
                    for($i=0;$i<12;$i++){
                    $recherchedetail[$value][$i] = "0.00";   
                }}
                $expenssesdetail->setDetail($detaillist);
                $expenssesdetail->setDetailProduction($productiondetail);
                $expenssesdetail->setDetailCommercial($commercialdetail);
                $expenssesdetail->setDetailRecherche($recherchedetail);

                $expenssesdetail->setGeneralexpenses($expenses);
                $expenssesdetail->setStatus(0);
                for($i=0 ;$i<$years;$i++){
                $expenssesdetail->setYear($i);
                $entityManager->merge($expenssesdetail);}
            //------------------------FIN--------------------------------------//
                $entityManager->flush();
              return $this->redirectToRoute('dashboard');
              
          }
         // dump($business);die();
          return $this->render('business/create.html.twig',['form' => $form->createView()]);
     
    }
    /**
     * @Route("/{_locale}/dashboard/mybusinessplan/{code}", name="monbuisnessplan")
     * 
     */
    public function show(Request $request,$code)
    {
        $business = new Businessplan();
        $sales = new Sales();   
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $business = $em->getRepository(Businessplan::class)->findOneByCode($code);
        if($business->getUser() == $user){
            $this->container->get('session')->set('business', $business); 
            return $this->redirectToRoute('mybusinessplan');
        }
        
      //  $product->setBusinessplan($business);
       // dump($product);die();
      // return $this->render('business/monbuisness.html.twig',['business' => $business]);
       return $this->redirectToRoute('dashboard');

    }
     /**
     * @Route("/{_locale}/dashboard/mybusinessplan", name="mybusinessplan")
     * 
     */
    public function dashboard(Request $request){
       $businessSession =$this->container->get('session')->get('business');
       
        return $this->render('business/monbuisness.html.twig',['business' => $businessSession]);
    }
         /**
     * @Route("/{_locale}/dashboard/my-business-plan/delete/{code}", name="businessdelete")
     * 
     */
    public function delete(Request $request,$code){
        $entityManager = $this->getDoctrine()->getManager();//Salesdetailled
        $business = $entityManager->getRepository(Businessplan::class)->findByCode($code);
        $product = $entityManager->getRepository(Product::class)->findByBusinessplan($business[0]);
        $sales = $entityManager->getRepository(Sales::class)->find($business[0]->getSales());
        $salesdetailled = $entityManager->getRepository(Salesdetailled::class)->findBySales($sales);
        //dump(count($product));die();
        for($i = 0 ; $i<count($salesdetailled); $i++){
            $entityManager->remove($salesdetailled[$i]);
        }
        for($i = 0 ; $i<count($product); $i++){
            $entityManager->remove($product[$i]);
        }
        $entityManager->remove($business[0]);
        $entityManager->remove($sales);
        $entityManager->flush();
        return $this->redirectToRoute('dashboard');
    }
     /**
     * @Route("/{_locale}/dashboard/my-business-plan/parametre", name="businessparametre")
     * 
     */
    public function parametre(Request $request){
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $business = $entityManager->getRepository(Businessplan::class)->find($businessSession->getId());
        
      
        $form = $this->createForm(BusinessFormType::class, $business);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $business->setNumberofyears($business->getNumberofyears());
            $entityManager->flush();
            return $this->redirectToRoute('dashboard');

        }
         return $this->render('business/parametre.html.twig',['form' => $form->createView(),'business' => $business]);
     }
     /**
     * @Route("/{_locale}/dashboard/my-business-plan/detailadd", name="detaile")
     * 
     */
    public function detailadd(){
        //dump($this->container->get('session'));
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $business = $entityManager->getRepository(Businessplan::class)->find($businessSession->getId());
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($business);
        $oldrange = $businessSession->getRangeofdetail();
        $oldlist = $generalexpensses[0]->getAdministration();
        $oldlistpro = $generalexpensses[0]->getProduction();
        $oldlistcom = $generalexpensses[0]->getSales();
        $oldlistrec = $generalexpensses[0]->getResearch();
        $listdefrais=[];
        $listdefraispro=[];
        $listdefraiscom=[];
        $listdefraisrec=[];
        foreach($oldlist as $key=>$value){
            array_push($listdefrais,$key);
        }
        foreach($oldlistpro as $key=>$value){
            array_push($listdefraispro,$key);
        }
        foreach($oldlistcom as $key=>$value){
            array_push($listdefraiscom,$key);
        }
        foreach($oldlistrec as $key=>$value){
            array_push($listdefraisrec,$key);
        }
        //$listdefrais =["eau","autre","loyers","assurance","honorairec","honorairej","impotettax","proprietes","deplacement","posteettelecom","fournitureentretient","fournitureadmenstrative"];
        foreach($listdefrais as $value){
        array_shift($oldlist[$value]);
       }
       foreach($listdefraispro as $value){
        array_shift($oldlistpro[$value]);
       }
       foreach($listdefraiscom as $value){
        array_shift($oldlistcom[$value]);
       }
       foreach($listdefraisrec as $value){
        array_shift($oldlistrec[$value]);
       }
        //dump($oldlist);die();
        $business->setRangeofdetail($oldrange+1);
        $generalexpensses[0]->setAdministration($oldlist);
        $generalexpensses[0]->setProduction($oldlistpro);
        $generalexpensses[0]->setSales($oldlistcom);
        $generalexpensses[0]->setResearch($oldlistrec);
        $entityManager->flush();
        $this->container->get('session')->set('business', $business); 
        //dump(get_class_methods($this->container->get('session')));die();
        return $this->redirectToRoute('businessparametre');
    }
}
