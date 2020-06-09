<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Impotsursociete;
use App\Form\ISFormType;
use Symfony\Component\HttpFoundation\Request;
class ISController extends AbstractController
{
    /**
     * @Route("/{_locale}/dashboard/my-business-plan/is-year-{id}", name="IS")
     */
    public function index($id,Request $request)
    {
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $years = $businessSession->getNumberofyears();
        $Impotsursociete = $entityManager->getRepository(Impotsursociete::class)->findBy(['businessplan' => $businessSession , 'year'=> $id]);
        $exsist = True ;
        //---------------intialiser les liste necessaires ------------------------------//
        for($i=0;$i<$years+1;$i++){
        for($x=0;$x<13;$x++){

        $ISdudebut[$i][$x] = "0.00";
        $ISdufin[$i][$x] = "0.00";
        }}
         
        //------------------------fin--------------------------------------------------//
        if($Impotsursociete == []){
            $Impotsursociete = new Impotsursociete();
            $Impotsursociete->setBusinessplan($businessSession);
            for($i=0; $i<$years;$i++){
              $Impotsursociete->setVersement([0,0,0,0,0,0,0,0,0,0,0,0]);
              $Impotsursociete->setRemboursement([0,0,0,0,0,0,0,0,0,0,0,0]);         
              $Impotsursociete->setYear($i);
              $entityManager->merge($Impotsursociete);
            }
            $exsist = false ;
            $entityManager->flush();
        }
        if($exsist == true){
                $form = $this->createForm(ISFormType::class , $Impotsursociete[0]);}
                else{
             $form = $this->createForm(ISFormType::class , $Impotsursociete);
                }
            
                $form->handleRequest($request);
               if($form->isSubmitted() && $form->isValid()){
                   $compteresultat = $form->getData();
                   if($exsist == false){
                     $entityManager->merge($Impotsursociete);
                   }
                   $entityManager->flush();
               }
               $Impotsursociete = $entityManager->getRepository(Impotsursociete::class)->findBy(['businessplan' => $businessSession]);
               //---------------------calcul du de debut ---------------------------------------------//
               $valeur = 0 ;
               $valeur2 = 0 ;
               for($i=0;$i<$years;$i++){
               foreach($Impotsursociete[$i]->getVersement() as $key=>$chiffres){
                $valeur  -=$chiffres;
                $ISdudebut[$i][$key+1] += $valeur;
                $ISdufin[$i][$key] += $valeur ;
               if($key+1 == 12 ){
                $ISdudebut[$i+1][0] += $valeur;
               }}
                           // dump($ISdudebut);die();
            }
          
            for($x=0 ; $x<$years;$x++){
                foreach($Impotsursociete[$x]->getRemboursement() as $key=>$chiffres){
               
                    $valeur2 +=$chiffres;
                    $ISdudebut[$x][$key+1] += $valeur2;
                    $ISdufin[$x][$key] += $valeur2 ;
                   }
                   if($key+1 == 12 ){
                    $ISdudebut[$x+1][0] += $valeur2;
                   }
            }
           
               //--------------------fin---------------------------------------------------------------//
            
               
       
        return $this->render('is/index.html.twig', [
            'business' => $businessSession,'form' => $form->createView(),'ISdudebut' => $ISdudebut,'id'=> $id
            ,'ISdufin'=> $ISdufin,
        ]);
    }
}
