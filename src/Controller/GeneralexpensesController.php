<?php

namespace App\Controller;

use App\Entity\Generalexpenses;
use App\Form\GeneralexpensesFormType;
use App\Entity\Generalexpensesdetail;
use App\Form\GeneralexpensesdetailFormType;
use App\Form\TopicFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\GeneralexpensesRepository;
/**
* @Route("/{_locale}/dashboard/my-business-plan/generalexpenses")
 */
class GeneralexpensesController extends AbstractController
{

    /**
     * @Route("/", name="generalexpenses")
     */
    public function index(Request $request,GeneralexpensesRepository $generalrep)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $businessSession =$this->container->get('session')->get('business');
        $generalexpensses = new  Generalexpenses();
        $generalexpenssesde = new Generalexpensesdetail();
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($businessSession);
        $generalexpenssesde = $entityManager->getRepository(Generalexpensesdetail::class)->findByGeneralexpenses($generalexpensses);
        $years = $businessSession->getNumberofyears();
        $rangeofdetail = $businessSession->getRangeofdetail();
        //------------ Partie pour l'ajout de topic--------------//
        $newlist= $generalexpensses[0]->getAdministration();
        $listdefrais =["eau","autre","loyers","assurance","honorairec","honorairej","impotettax","proprietes","deplacement","posteettelecom","fournitureentretient","fournitureadmenstrative"];
        foreach($listdefrais as $value){
         $originallist[$value] =   $generalexpensses[0]->getAdministration()[$value];
        }
      
        $diff =  array_diff_key($newlist,$originallist);
        //dump($newlist,$originallist,$diff);die();
        //------------Fin de partie-----------------//
        $rangeofglobal = count($generalexpensses[0]->getAdministration()['eau']);
        $globalTotal =[];
        for ($i=0 ; $i<$rangeofglobal ; $i++){
            $globalTotal[$i] =  0.00 ;}
        for ($i=0 ; $i<$years ; $i++){
        $total[$i] =  0.00 ;}
       // $etat =$generalexpenssesde[0]->getStatus(); 
        
        for ($i=0 ; $i<$years ; $i++){
       foreach($generalexpenssesde[$i]->getDetail() as $key=>$list){
        
    
        $Sum[$i][$key] = array_sum($list);
       $total[$i] +=  $Sum[$i][$key];
        // dump($list);die();
       }}
       for ($i=0 ; $i<$rangeofglobal ; $i++){
       foreach($generalexpensses[0]->getAdministration() as $value){
        $globalTotal[$i] += $value[$i];
   }
}
   //dump($globalTotal);die();
    //-------Partie pour generer un tableau pour les frais generaux si n'existe pas ----------//
   /* if($generalexpensses==null){
        $newgeneralexpensses = new  Generalexpenses();
        $listdefrais =["eau","autre","loyers","assurance","honorairec","honorairej","impotettax","proprietes","deplacement","posteettelecom","fournitureentretient","fournitureadmenstrative"];
        $newgeneralexpensses->setBusinessplan($businessSession);
        foreach($listdefrais as $value){
        for($i=0;$i<($years-$rangeofdetail);$i++){
         $admisinstrator[$value][$i] = 0.00;
        }

        }
        
        $newgeneralexpensses->setAdministration($admisinstrator);
    }
    */
    //-------Fin de cette Partie
  // dump($Sum);die();
       //fin de calcul
        $tvalist = $generalexpensses[0]->getTVAlist();
        $form = $this->createForm(GeneralexpensesFormType::class, $generalexpensses[0]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $generalexpensses=$form->getData();     
             // dump($sales->getdetailled());die();
        
          //dump($finalCA);die();
              //$entityManager->merge($sales);
              $entityManager->flush();
      
              return $this->redirectToRoute('generalexpenses');}
        return $this->render('generalexpenses/index.html.twig' ,['business'=>$businessSession, 'form' => $form->createView(),'somme'=> $Sum,'total'=>$total,
        'globalTotal' => $globalTotal , 'diff' => $diff ,'tva' => $tvalist
         ]);
    }

    /**
     * @Route("-year-{id}", name="generalexpensesdetail")
     */
    public function detail($id,Request $request){

        $entityManager = $this->getDoctrine()->getManager();
        $businessSession =$this->container->get('session')->get('business');
        $years = $businessSession->getNumberofyears();
        $generalexpenssesdetail = new  Generalexpensesdetail();
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($businessSession);
        $years = $businessSession->getNumberofyears();
        $generalexpenssesdetail = $entityManager->getRepository(Generalexpensesdetail::class)->findBy(['year' => $id ,'generalexpenses' =>$generalexpensses] ); // il faut deux parametre ici (annes et ID)
        //------------------------GENERER LISTE TOPIC--------------------------------------
        $originallist ;
        $listdefrais =["eau","autre","loyers","assurance","honorairec","honorairej","impotettax","proprietes","deplacement","posteettelecom","fournitureentretient","fournitureadmenstrative"];
        foreach($listdefrais as $value){
            $originallist[$value] =   $generalexpenssesdetail[0]->getDetail()[$value];
           }
        $diff =  array_diff_key($generalexpenssesdetail[0]->getDetail(),$originallist);
        
        //------------------------FIN------------------------------------------------------
        //------------------------CALCUL DE SOMME------------------------------------------
        $total=0;
        for($x=0;$x<12;$x++){
            $Sumpermonth [$x] = 0.00 ;
        }
            foreach($generalexpenssesdetail[0]->getDetail() as $key=>$list){//Somme par cle
             
        
             $Sum[$id][$key] = array_sum($list);
             $total += array_sum($list);
            }
             for($x=0;$x<12;$x++){
            foreach($generalexpenssesdetail[0]->getDetail() as $key=>$list){//Somme par Mois
             $index =0 ;
        
                $Sumpermonth[$x] += $list[$x];
                
               }}
        //------------------------FIN DE CALCUL       
        $form = $this->createForm(GeneralexpensesdetailFormType::class, $generalexpenssesdetail[0]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $generalexpenssesdetail=$form->getData();     
             // dump($sales->getdetailled());die();
        
          //dump($finalCA);die();
              //$entityManager->merge($sales);
              $entityManager->flush();
      
              return $this->redirectToRoute('generalexpenses');}
        return $this->render('generalexpenses/detailled.html.twig',['business'=>$businessSession,'form' => $form->createView(),'id'=> $id,'somme'=>$Sum ,'total'=>$total,'sumpermonth'=>$Sumpermonth
        ,'diff'=> $diff
        ]);
    }
    /**
     * @Route("/topic", name="topic")
     */
    public function topic(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $businessSession =$this->container->get('session')->get('business');
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($businessSession);
        $generalexpenssesdetail = $entityManager->getRepository(Generalexpensesdetail::class)->findBy(['generalexpenses' =>$generalexpensses] );
        $rangeofdetail = $businessSession->getRangeofdetail();
        $years = $businessSession->getNumberofyears();
        $newlist = $generalexpensses[0]->getAdministration();
        $tvalist = $generalexpensses[0]->getTVAlist();
        $form = $this->createForm(TopicFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            for($i =0 ; $i <($years-$rangeofdetail) ; $i++){
                $newlist[$form->getData()['Name']][$i] = "0.00";}
            for($i =0 ; $i <$years ; $i++){
                $detail = $generalexpenssesdetail[$i]->getDetail();
                for($x =0 ; $x<12;$x++){
            
             $detail[$form->getData()['Name']][$x]="0.00";
            
            }
            $generalexpenssesdetail[$i]->setDetail($detail);
            }
            
            $tvalist[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
            $generalexpensses[0]->setTVAlist($tvalist);
            $generalexpensses[0]->setAdministration($newlist);
           
            $entityManager->flush();
            return $this->redirectToRoute('generalexpenses');  
        }
        return $this->render('generalexpenses/topic.html.twig',[
            'form' => $form->createView() 
        ]);
    }
     /**
     * @Route("/edit/{name}", name="edit")
     */
    public function edit(Request $request,$name){

        $entityManager = $this->getDoctrine()->getManager();
        $businessSession =$this->container->get('session')->get('business');
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($businessSession);
        $generalexpenssesdetail = $entityManager->getRepository(Generalexpensesdetail::class)->findBy(['generalexpenses' =>$generalexpensses] );
        $tva = $generalexpensses[0]->getTVAlist()[$name];
        return $this->render('generalexpenses/edit.html.twig',[
        'name'=> $name, 'tva' => $tva

        ]);
    }
}