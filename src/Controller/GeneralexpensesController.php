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
        //------------ Partie pour afficher le  topic--------------//
        $newlist= $generalexpensses[0]->getAdministration();
        $newlistpro= $generalexpensses[0]->getProduction();
        $newlistsales= $generalexpensses[0]->getSales();
        $newlistrec= $generalexpensses[0]->getResearch();
        $listdefrais =["eau","autre","loyers","assurance","honorairec","honorairej","impotettax","proprietes","deplacement","posteettelecom","fournitureentretient","fournitureadmenstrative"];
        $listProduction =["bail","petiteq","emballage","delpacement","autre"];
        $listCommercial =["publicite","evenmentiel","conseils","documentation","promotions","salons","deplacementsttc","autre"];
        $listRecherche = ["petiteq","conseils","congres","deplacement","autre"]; 
        foreach($listdefrais as $value){
         $originallist[$value] =   $generalexpensses[0]->getAdministration()[$value];
        }
        foreach($listProduction as $value){
            $originallistpro[$value] =   $generalexpensses[0]->getProduction()[$value];
        }
        foreach($listCommercial as $value){
            $originallistcom[$value] =   $generalexpensses[0]->getSales()[$value];
        }
        foreach($listRecherche as $value){
            $originallistrec[$value] =   $generalexpensses[0]->getResearch()[$value];
        }
        $diff =  array_diff_key($newlist,$originallist);
        $diffpro=  array_diff_key($newlistpro,$originallistpro);
        $diffcom =  array_diff_key($newlistsales,$originallistcom);
        $diffrec =  array_diff_key($newlistrec,$originallistrec);
        //dump($diffpro,$diffcom,$diff);die();
        //------------Fin de partie-----------------//
        $rangeofglobal = count($generalexpensses[0]->getAdministration()['eau']);
        $globalTotal =[];
        for ($i=0 ; $i<$rangeofglobal ; $i++){
            $globalTotal[$i] =  0.00 ;
            $globalTotalpro[$i] =  0.00 ;
            $globalTotalcom[$i]=0.00;
            $globalTotalrec[$i]=0.00;}
        for ($i=0 ; $i<$years ; $i++){
        $total[$i] =  0.00 ;
        $totalpro[$i] = 0.00;
        $totalcom[$i] = 0.00;
        $totalrech[$i] = 0.00;
    }
       // $etat =$generalexpenssesde[0]->getStatus(); 
       //-------------------------------------Adimnistration---------------- 
        for ($i=0 ; $i<$years ; $i++){
       foreach($generalexpenssesde[$i]->getDetail() as $key=>$list){
        
    
        $Sum[$i][$key] = array_sum($list);
       $total[$i] +=  $Sum[$i][$key];
        // dump($list);die();
       }}
       //-----------------------------------Fin------------------------
       //-----------------------------------Production------------------------
       for ($i=0 ; $i<$years ; $i++){
        foreach($generalexpenssesde[$i]->getDetailProduction() as $key=>$list){
         
     
         $Sumproduction[$i][$key] = array_sum($list);
        $totalpro[$i] +=  $Sumproduction[$i][$key];
         // dump($list);die();
        }}
       //----------------------------------Fin------------------------------
       //----------------------------Commercial----------------------------
       for ($i=0 ; $i<$years ; $i++){
        foreach($generalexpenssesde[$i]->getDetailCommercial() as $key=>$list){
         
     
         $Sumcommercial[$i][$key] = array_sum($list);
        $totalcom[$i] +=  $Sumcommercial[$i][$key];
         // dump($list);die();
        }}
        //-------------------------------Fin-----------------------
        //-----------------------------recherche--------------------
        for ($i=0 ; $i<$years ; $i++){
            foreach($generalexpenssesde[$i]->getDetailRecherche() as $key=>$list){
             
         
             $Sumrecherche[$i][$key] = array_sum($list);
            $totalrech[$i] +=  $Sumrecherche[$i][$key];
             // dump($list);die();
            }}
        //-----------------------------Fin----------------------
       for ($i=0 ; $i<$rangeofglobal ; $i++){
       foreach($generalexpensses[0]->getAdministration() as $value){
        $globalTotal[$i] += $value[$i];
   }
   }
   for ($i=0 ; $i<$rangeofglobal ; $i++){
    foreach($generalexpensses[0]->getProduction() as $value){
     $globalTotalpro[$i] += $value[$i];
}
}
for ($i=0 ; $i<$rangeofglobal ; $i++){
    foreach($generalexpensses[0]->getSales() as $value){
     $globalTotalcom[$i] += $value[$i];
}
}
for ($i=0 ; $i<$rangeofglobal ; $i++){
    foreach($generalexpensses[0]->getResearch() as $value){
     $globalTotalrec[$i] += $value[$i];
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
        $tvalistpro = $generalexpensses[0]->getTvalistproduction();
        $tvalistcom = $generalexpensses[0]->getTvalistcommercial();
        $tvalistrec = $generalexpensses[0]->getTvalistrecherche();
        $form = $this->createForm(GeneralexpensesFormType::class, $generalexpensses[0]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $generalexpensses=$form->getData();     
             // dump($sales->getdetailled());die();
        
          //dump($finalCA);die();
              //$entityManager->merge($sales);
              $entityManager->flush();
      
              return $this->redirectToRoute('generalexpenses');}
        return $this->render('generalexpenses/index.html.twig' ,['business'=>$businessSession, 'tvacom'=> $tvalistcom ,'tvarec' =>$tvalistrec,
        'form' => $form->createView(),'somme'=> $Sum,'total'=>$total, 'sumproduction'=>$Sumproduction,'tvapro'=>$tvalistpro,
        'globalTotal' => $globalTotal , 'diff' => $diff ,'diffpro'=> $diffpro,'diffcom'=>$diffcom,'diffrec'=> $diffrec,'tva' => $tvalist , 'totalproduction'=>$totalpro, 
        'globaltotalpro'=> $globalTotalpro, 'sumcommercial' => $Sumcommercial , 'totalcommercial'=> $totalcom,
         'globaltotalcom' => $globalTotalcom , 'sumrecherche' => $Sumrecherche , 'totalrecherche' => $totalrech
        ,'globaltotalrec' => $globalTotalrec ]); 
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
        $listProduction =["bail","petiteq","emballage","deplacement","autre"];
        $listCommercial =["publicite","evenmentiel","conseils","documentation","promotions","salons","deplacementsttc","autre"];
        $listRecherche = ["petiteq","conseils","congres","deplacement","autre"];  
        foreach($listdefrais as $value){
            $originallist[$value] =   $generalexpenssesdetail[0]->getDetail()[$value];
           }
        foreach($listProduction as $value){
            $originallistpro[$value] =   $generalexpenssesdetail[0]->getDetailProduction()[$value];
        }
        foreach($listCommercial as $value){
            $originallistcom[$value] =   $generalexpenssesdetail[0]->getDetailCommercial()[$value];
        }
        foreach($listRecherche as $value){
            $originallistrec[$value] =   $generalexpenssesdetail[0]->getDetailRecherche()[$value];
        }   
        $diff =  array_diff_key($generalexpenssesdetail[0]->getDetail(),$originallist);
        $diffpro=  array_diff_key($generalexpenssesdetail[0]->getDetailProduction(),$originallistpro);
        $diffcom =  array_diff_key($generalexpenssesdetail[0]->getDetailCommercial(),$originallistcom);
        $diffrec =  array_diff_key($generalexpenssesdetail[0]->getDetailRecherche(),$originallistrec);
        //------------------------FIN------------------------------------------------------
        //------------------------CALCUL DE SOMME------------------------------------------
        $total=0;
        $totalProduction=0 ;
        $totalCommercial=0;
        $totalRecherche=0;
        for($x=0;$x<12;$x++){
            $Sumpermonth [$x] = 0.00 ;
            $SumpermonthProduction[$x]=0.00 ;
            $SumpermonthCommercial[$x] = 0.00 ;
            $SumpermonthRecherche[$x] = 0.00;
        }
            foreach($generalexpenssesdetail[0]->getDetail() as $key=>$list){//Somme par cle
             
        
             $Sum[$id][$key] = array_sum($list);
             $total += array_sum($list);
            }
            foreach($generalexpenssesdetail[0]->getDetailProduction() as $key=>$list){
             
        
                $Sumproduction[$id][$key] = array_sum($list);
                $totalProduction += array_sum($list);
               }
            foreach($generalexpenssesdetail[0]->getDetailCommercial() as $key=>$list){
             
        
                $Sumcommercial[$id][$key] = array_sum($list);
                $totalCommercial += array_sum($list);
               }
            foreach($generalexpenssesdetail[0]->getDetailRecherche() as $key=>$list){
             
        
                $Sumrecherche[$id][$key] = array_sum($list);
                $totalRecherche += array_sum($list);
               }

             for($x=0;$x<12;$x++){
            foreach($generalexpenssesdetail[0]->getDetail() as $key=>$list){//Somme par Mois
             $index =0 ;
        
                $Sumpermonth[$x] += $list[$x];
                
               }}
               for($x=0;$x<12;$x++){
                foreach($generalexpenssesdetail[0]->getDetailProduction() as $key=>$list){
                 $index =0 ;
            
                    $SumpermonthProduction[$x] += $list[$x];
                    
                   }}
                for($x=0;$x<12;$x++){
                foreach($generalexpenssesdetail[0]->getDetailCommercial() as $key=>$list){
                 $index =0 ;
            
                    $SumpermonthCommercial[$x] += $list[$x];
                    
                   }}
               for($x=0;$x<12;$x++){
                    foreach($generalexpenssesdetail[0]->getDetailRecherche() as $key=>$list){
                     $index =0 ;
                
                        $SumpermonthRecherche[$x] += $list[$x];
                        
                       }}
            
        //------------------------FIN DE CALCUL------------------------------//
        //-------------------------Somme total------------------------------------//
        $totalsomme = $total + $totalProduction +  $totalCommercial + $totalRecherche;
        for($i=0 ; $i<12;$i++){
        $totalpermonth[$i]= $Sumpermonth[$i] + $SumpermonthProduction[$i] + $SumpermonthCommercial[$i]+$SumpermonthRecherche[$i];
        }
        //---------------------------Fin----------------------------------------------//
    
        $form = $this->createForm(GeneralexpensesdetailFormType::class, $generalexpenssesdetail[0]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $generalexpenssesdetail=$form->getData();     
             // dump($sales->getdetailled());die();
        
          //dump($finalCA);die();
              //$entityManager->merge($sales);
              $entityManager->flush();
      
              return $this->redirectToRoute('generalexpenses');}
        return $this->render('generalexpenses/detailled.html.twig',['business'=>$businessSession,'form' => $form->createView(),
        'id'=> $id,'somme'=>$Sum ,'total'=>$total,'sumpermonth'=>$Sumpermonth ,'sumpermonthproduction'=>$SumpermonthProduction  , 'sumpermonthcommercial'=>$SumpermonthCommercial
        ,'diff'=> $diff ,'diffpro'=> $diffpro,'diffcom'=>$diffcom,'diffrec'=>$diffrec ,'sommeproduction' => $Sumproduction , 'totalproduction' => $totalProduction, 'totalcommercial' =>$totalCommercial,'sumpermonthrecherche' =>$SumpermonthRecherche,
        'sumcommercial'=>$Sumcommercial, 'sumrecherche' => $Sumrecherche , 'totalrecherche' => $totalRecherche,
        'totalsomme'=> $totalsomme ,'totalpermonth' =>$totalpermonth,
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
        $newlistproduction = $generalexpensses[0]->getProduction();
        $newlistcommercial = $generalexpensses[0]->getSales();
        $newlistrecherceh = $generalexpensses[0]->getResearch();
        $tvalist = $generalexpensses[0]->getTVAlist();
        $tvalistproduction = $generalexpensses[0]->getTvalistproduction();
        $tvalistcommercial = $generalexpensses[0]->getTvalistcommercial();
        $tvalistrecherche = $generalexpensses[0]->getTvalistrecherche();
        $form = $this->createForm(TopicFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            for($i =0 ; $i <($years-$rangeofdetail) ; $i++){
                if($form->getData()['Departement']==0){
                $newlist[$form->getData()['Name']][$i] = "0.00";}
                else if($form->getData()['Departement']==1){
                $newlistproduction[$form->getData()['Name']][$i] = "0.00";
                }
                else if($form->getData()['Departement']==2){
                $newlistcommercial[$form->getData()['Name']][$i] = "0.00";
                }
                else if($form->getData()['Departement']==3){
                $newlistrecherceh[$form->getData()['Name']][$i] = "0.00";
                }

            }
            for($i =0 ; $i <$years ; $i++){
                if($form->getData()['Departement']==0){
                $detail = $generalexpenssesdetail[$i]->getDetail();}
                else if($form->getData()['Departement']==1){
                $detail = $generalexpenssesdetail[$i]->getDetailProduction();
                }
                else if($form->getData()['Departement']==2){
                    $detail = $generalexpenssesdetail[$i]->getDetailCommercial();
                }
                else if($form->getData()['Departement']==3){
                    $detail = $generalexpenssesdetail[$i]->getDetailRecherche();
                }
            for($x =0 ; $x<12;$x++){
            
             $detail[$form->getData()['Name']][$x]="0.00";
            
            }
            if($form->getData()['Departement']==0){
            $generalexpenssesdetail[$i]->setDetail($detail);}
            else if($form->getData()['Departement']==1){
            $generalexpenssesdetail[$i]->setDetailProduction($detail);}
            else if($form->getData()['Departement']==2){
            $generalexpenssesdetail[$i]->setDetailCommercial($detail);}
            else if($form->getData()['Departement']==3){
            $generalexpenssesdetail[$i]->setDetailRecherche($detail);}
            }
            
            
           
            if($form->getData()['Departement']==0){
            $tvalist[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
            $generalexpensses[0]->setTVAlist($tvalist);
            $generalexpensses[0]->setAdministration($newlist);
            }
            else if($form->getData()['Departement']==1){
              $tvalistproduction[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
             $generalexpensses[0]->setTvalistproduction($tvalistproduction);   
             $generalexpensses[0]->setProduction($newlistproduction);
            }
            else if($form->getData()['Departement']==2){
            $tvalistcommercial[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
            $generalexpensses[0]->setTvalistcommercial($tvalistcommercial);
            $generalexpensses[0]->setSales($newlistcommercial);
            }
            else if($form->getData()['Departement']==3){
            $tvalistrecherche[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
            $generalexpensses[0]->setTvalistrecherche($tvalistrecherche);
            $generalexpensses[0]->setResearch($newlistrecherceh);
            }
            
            $entityManager->flush();
            return $this->redirectToRoute('generalexpenses');  
        }
        return $this->render('generalexpenses/topic.html.twig',[
            'form' => $form->createView() 
        ]);
    }
    private $topicname ;
     /**
     * @Route("/edit/{name}", name="edit")
     */
    public function edit(Request $request,$name){

        $entityManager = $this->getDoctrine()->getManager();
        $businessSession =$this->container->get('session')->get('business');
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($businessSession);
        $generalexpenssesdetail = $entityManager->getRepository(Generalexpensesdetail::class)->findBy(['generalexpenses' =>$generalexpensses] );
        $list = $generalexpensses[0]->getAdministration() ;
        $tvalist = $generalexpensses[0]->getTVAlist() ; 
       // unset($list[$name]); delete key from list
        //dump($list);die();
        $tva = $generalexpensses[0]->getTVAlist()[$name];
        $this->topicname = $name;
        $form = $this->createForm(TopicFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->getData()['Name']!= $name){
            $list[$form->getData()['Name']] = $list[$name];
            unset($list[$name]);
            unset($tvalist[$name]);
            $tvalist[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
            //$generalexpensses[0]->getTVAlist()[$name] = 
            dump($list,$tvalist,$generalexpenssesdetail);die();
            }
            return $this->redirectToRoute('generalexpenses');
        }
        return $this->render('generalexpenses/edit.html.twig',[
        'name'=> $name, 'tva' => $tva , 'form' => $form->createView()

        ]);
    }
     /**
     * @Route("/edit/rename", name="rename")
     */
    public function rename(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $businessSession =$this->container->get('session')->get('business');
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($businessSession);
        $generalexpenssesdetail = $entityManager->getRepository(Generalexpensesdetail::class)->findBy(['generalexpenses' =>$generalexpensses] );
        $list = $generalexpensses[0]->getAdministration() ;
        $list[$newkey] = $list[$oldkey];
        unset($list[$oldkey]);
        //$entityManager->flush();
        return $this->render('generalexpenses');
    }
    /**
     * @Route("/edit/delete", name="delete")
     */
    public function delete(Request $request){


    }

}