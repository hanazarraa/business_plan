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
    static $staticpurchase ;
    static $fraisAdmdetail;
    static $fraisProdetail;
    static $fraisComdetail ; 
    static $fraisRDdetail ; 

    static $fraisAdmglob ;
    static $fraisProglob ;
    static $fraisComglob ;
    static $fraisRDglob ;

    static $listAdm ;
    static $listPro ;
    static $listCom ; 
    static $listRD ;

    static $generalAdmwithaname;
    static $generalProwithaname;
    static $generalComwithaname;
    static $generalRecwithaname;

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
        $globalTotalpro=[];
        $globalTotalcom = [];
        $globalTotalrec = [];
        for ($i=0 ; $i<$rangeofglobal ; $i++){
            $globalTotal[$i] =  0.00 ;
            $globalTotalpro[$i] =  0.00 ;
            $globalTotalcom[$i]=0.00;
            $globalTotalrec[$i]=0.00;}
        for ($i=0 ; $i<$years ; $i++){
        $total[$i] =  0.00 ;
        $totalpro[$i] = 0.00;
        $totalcom[$i] = 0.00;
        $totalrech[$i] = 0.00;}
       
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

    //merge les valeur globale avec les valeur detailler dans une seule liste 
    $pos =0 ;
    for($i=0 ; $i<$years ; $i++){
        if($pos < $rangeofdetail){
        $finalgeneralexpenses[$i] = $total[$i] + $totalpro[$i] + $totalcom[$i] + $totalrech[$i];
        $pos ++;}
        else{
            $finalgeneralexpenses[$i]  = $globalTotal[$i-$rangeofdetail] + $globalTotalpro[$i-$rangeofdetail] +  $globalTotalcom[$i-$rangeofdetail] + $globalTotalrec[$i-$rangeofdetail];
        }
    }
    self::$staticpurchase =$finalgeneralexpenses ; 
    self::$fraisAdmdetail  = $total ;
    self::$fraisProdetail = $totalpro;
    self::$fraisComdetail = $totalcom ;
    self::$fraisRDdetail = $totalrech ;

    self::$fraisAdmglob = $globalTotal;
    self::$fraisProglob = $globalTotalpro;
    self::$fraisComglob = $globalTotalcom;
    self::$fraisRDglob = $globalTotalrec;

    //-------------------------fin de merge ------------------------------//
        $form = $this->createForm(GeneralexpensesFormType::class, $generalexpensses[0]);
        $form->handleRequest($request);
        //
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
    public function getpurchase(){
    
        return self::$staticpurchase;
    }
    public function getfraisAdmG(){
    
        return self::$fraisAdmglob;
    }
    public function getfraisProG(){
    
        return self::$fraisProglob;
    }
    public function getfraisComG(){
        return self::$fraisComglob;
    }
    public function getfraisRDG(){
       return self::$fraisRDglob   ;
    }
    public function getfraisAdmD(){   
        return self::$fraisAdmdetail;
    }
    public function getfraisProD(){   
        return self::$fraisProdetail;
    }
    public function getfraisComD(){
        return self::$fraisComdetail;
    }
    public function getfraisRDD(){
        return self::$fraisRDdetail;
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
        $generalexpenssesdetailallyears = $entityManager->getRepository(Generalexpensesdetail::class)->findBy (['generalexpenses' =>$generalexpensses] ); // il faut deux parametre ici (annes et ID)
        for($i = 0 ; $i < $years ;$i++){//cette boucle pour exporter les donnée dans la partie TVA 
        $geneAdm[$i] = $generalexpenssesdetailallyears[$i]->getDetail();
        $genePro[$i] = $generalexpenssesdetailallyears[$i]->getDetailProduction();
        $geneCom[$i] = $generalexpenssesdetailallyears[$i]->getDetailCommercial();
        $geneRec[$i] = $generalexpenssesdetailallyears[$i]->getDetailRecherche();
        }
         
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
        self::$listPro =  $SumpermonthProduction ; 
        self::$generalAdmwithaname = $geneAdm;
        self::$generalProwithaname = $genePro;
        self::$generalComwithaname = $geneCom;
        self::$generalRecwithaname = $geneRec;
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
              //dump(self::$listPro);die();
        return $this->render('generalexpenses/detailled.html.twig',['business'=>$businessSession,'form' => $form->createView(),
        'id'=> $id,'somme'=>$Sum ,'total'=>$total,'sumpermonth'=>$Sumpermonth ,'sumpermonthproduction'=>$SumpermonthProduction  , 'sumpermonthcommercial'=>$SumpermonthCommercial
        ,'diff'=> $diff ,'diffpro'=> $diffpro,'diffcom'=>$diffcom,'diffrec'=>$diffrec ,'sommeproduction' => $Sumproduction , 'totalproduction' => $totalProduction, 'totalcommercial' =>$totalCommercial,'sumpermonthrecherche' =>$SumpermonthRecherche,
        'sumcommercial'=>$Sumcommercial, 'sumrecherche' => $Sumrecherche , 'totalrecherche' => $totalRecherche,
        'totalsomme'=> $totalsomme ,'totalpermonth' =>$totalpermonth,
        ]);
    }
    public function getlistPro(){
        return self::$listPro;
    }
    public function getlistAdmwithname(){
    return self::$generalAdmwithaname ;
    }
    public function getlistProwithname(){
    return self::$generalProwithaname ;
    }
    public function getlistComwithname(){
    return self::$generalComwithaname ;
    }
    public function getlistRecwithname(){
    return self::$generalRecwithaname ;
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
        return $this->render('generalexpenses/topic.html.twig',['business'=>$businessSession,
            'form' => $form->createView() 
        ]);
    }
    public $topicname ;
    
     /**
     * @Route("/edit/{name}", name="edit")
     */
    public function edit(Request $request,$name){

        $entityManager = $this->getDoctrine()->getManager();
        $businessSession =$this->container->get('session')->get('business');
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($businessSession);
        $generalexpenssesdetail = $entityManager->getRepository(Generalexpensesdetail::class)->findBy(['generalexpenses' =>$generalexpensses] );
        $list = $generalexpensses[0]->getAdministration() ;
        $listpro =  $generalexpensses[0]->getProduction();
        $listcom = $generalexpensses[0]->getSales() ;
        $listrec =  $generalexpensses[0]->getResearch();
        $years = $businessSession->getNumberofyears();
         for($i = 0 ; $i<$years;$i++){
            $listdetail[$i]= $generalexpenssesdetail[$i]->getDetail();
            $listdetailPro[$i]= $generalexpenssesdetail[$i]->getDetailProduction();
            $listdetailCom[$i]= $generalexpenssesdetail[$i]->getDetailCommercial();
            $listdetailRec[$i]= $generalexpenssesdetail[$i]->getDetailRecherche();
         }
        $tvalist = $generalexpensses[0]->getTVAlist() ; 
        $tvalistpro = $generalexpensses[0]->getTvalistproduction();
        $tvalistcom = $generalexpensses[0]->getTvalistcommercial();
        $tvalistrec = $generalexpensses[0]->getTvalistrecherche();
        // unset($list[$name]); delete key from list
        //
        $status = '';
        $finaltva = [];
        /*$tva = $generalexpensses[0]->getTVAlist()[$name];
        $tvapro = $generalexpensses[0]->getTvalistproduction()[$name];
        $tvacom = $generalexpensses[0]->getTvalistcommercial()[$name];
        $tvarec = $generalexpensses[0]->getTvalistrecherche()[$name];*/
        
        if(array_key_exists($name, $tvalist) == true){
            $status = 'Administration';
            $finaltva = $generalexpensses[0]->getTVAlist()[$name];
        }
        
        if(array_key_exists($name, $tvalistpro) == true ){
            $status = 'Production';
            $finaltva = $generalexpensses[0]->getTvalistproduction()[$name];
        }
       
        if(array_key_exists($name, $tvalistcom) == true){
            $status = 'Commercial';
            $finaltva = $generalexpensses[0]->getTvalistcommercial()[$name]; 
        }
        if(array_key_exists($name, $tvalistrec) == true){
            $status = 'Recherche';
            $finaltva = $generalexpensses[0]->getTvalistrecherche()[$name];
            
        }
       
        
        //$finaltva = array_merge($tva,$tvapro,$tvacom,$tvarec) ;
        //dump($finaltva);die();
        $this->topicname = $name;
       
   // dump($this->topicname);die();
        $form = $this->createForm(TopicFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->getData()['Name']!= $name){
                if($status == 'Administration'){
                 $a = $list[$name];
                 unset($list[$name]);
                 unset($tvalist[$name]);
                 $generalexpensses[0]->setAdministration($list); 
                 $generalexpensses[0]->setTVAlist($tvalist) ; 
                 for($i = 0 ; $i<$years;$i++){
                    ${"d" . $i} = $listdetail[$i][$name];
                    unset($listdetail[$i][$name]);
                    $generalexpenssesdetail[$i]->setDetail($listdetail[$i]);
                 }
                }
                if($status == 'Production'){
                $a = $listpro[$name];
                unset($listpro[$name]);
                unset($tvalistpro[$name]); 
                $generalexpensses[0]->setProduction($listpro); 
                $generalexpensses[0]->setTvalistproduction($tvalistpro) ; 
                for($i = 0 ; $i<$years;$i++){
                    ${"d" . $i} = $listdetailPro[$i][$name];
                    unset($listdetailPro[$i][$name]);
                    $generalexpenssesdetail[$i]->setDetailProduction($listdetailPro[$i]);
                 }
            }
                if($status == 'Commercial'){
                $a = $listcom[$name];
                unset($listcom[$name]);
                unset($tvalistcom[$name]);
                $generalexpensses[0]->setSales($listcom); 
                $generalexpensses[0]->setTvalistcommercial($tvalistcom) ; 

                for($i = 0 ; $i<$years;$i++){
                    ${"d" . $i} = $listdetailCom[$i][$name];
                    unset($listdetailCom[$i][$name]);
                    $generalexpenssesdetail[$i]->setDetailCommercial($listdetailCom[$i]);
                 }
            }
                if($status == 'Recherche'){
                $a = $listrec[$name];
                unset($listrec[$name]);
                unset($tvalistrec[$name]); 
                $generalexpensses[0]->setRecherche($listrec); 
                $generalexpensses[0]->setTvalistrecherche($tvalistrec) ; 

                for($i = 0 ; $i<$years;$i++){
                    ${"d" . $i} = $listdetailRec[$i][$name];
                    unset($listdetailRec[$i][$name]);
                    $generalexpenssesdetail[$i]->setDetailRecherche($listdetailRec[$i]);
                 }
            }
   
            if($form->getData()['Departement']==0 ){
            $list[$form->getData()['Name']] = $a;
            $tvalist[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
            for($i = 0 ; $i<$years;$i++){
                $listdetail[$i][$form->getData()['Name']] = ${"d" . $i};
                $generalexpenssesdetail[$i]->setDetail($listdetail[$i]);
            }     
            $generalexpensses[0]->setAdministration($list); 
            $generalexpensses[0]->setTVAlist($tvalist) ; }
            

            if($form->getData()['Departement']==1 ){
            $listpro[$form->getData()['Name']] = $a;
            $tvalistpro[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
            for($i = 0 ; $i<$years;$i++){
                $listdetailPro[$i][$form->getData()['Name']] = ${"d" . $i};
                $generalexpenssesdetail[$i]->setDetailProduction($listdetailPro[$i]);
            }  
            $generalexpensses[0]->setProduction($listpro); 
            $generalexpensses[0]->setTvalistproduction($tvalistpro) ; }
            
            if($form->getData()['Departement']==2 ){
            $listcom[$form->getData()['Name']] = $a;
            $tvalistcom[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
            for($i = 0 ; $i<$years;$i++){
                $listdetailCom[$i][$form->getData()['Name']] = ${"d" . $i};
                $generalexpenssesdetail[$i]->setDetailCommercial($listdetailCom[$i]);
            } 
            $generalexpensses[0]->setSales($listcom); 
            $generalexpensses[0]->setTvalistcommercial($tvalistcom) ; }
          
            if($form->getData()['Departement']==3 ){
            $listrec[$form->getData()['Name']] = $a;
            $tvalistrec[$form->getData()['Name']] = ["".$form->getData()['VAT']] ;
            for($i = 0 ; $i<$years;$i++){
                $listdetailRec[$i][$form->getData()['Name']] = ${"d" . $i};
                $generalexpenssesdetail[$i]->setDetailRecherche($listdetailRec[$i]);
            } 
            $generalexpensses[0]->setRecherche($listrec); 
            $generalexpensses[0]->setTvalistrecherche($tvalistrec) ; }

            }
            $entityManager->flush();
            return $this->redirectToRoute('generalexpenses');
        }
        return $this->render('generalexpenses/edit.html.twig',['finaltva'=> $finaltva,
        'name'=> $name , 'form' => $form->createView()

        ]);
    }
  
    /**
     * @Route("/delete/{name}", name="delete")
     */
    public function delete(Request $request,$name){
        $entityManager = $this->getDoctrine()->getManager();
        $businessSession =$this->container->get('session')->get('business');
        $generalexpensses = $entityManager->getRepository(Generalexpenses::class)->findBybusinessplan($businessSession);
        $generalexpenssesdetail = $entityManager->getRepository(Generalexpensesdetail::class)->findBy(['generalexpenses' =>$generalexpensses] );
        $list = $generalexpensses[0]->getAdministration() ;
        $listpro =  $generalexpensses[0]->getProduction();
        $listcom = $generalexpensses[0]->getSales() ;
        $listrec =  $generalexpensses[0]->getResearch();
        $years = $businessSession->getNumberofyears();
         for($i = 0 ; $i<$years;$i++){
            $listdetail[$i]= $generalexpenssesdetail[$i]->getDetail();
            $listdetailPro[$i]= $generalexpenssesdetail[$i]->getDetailProduction();
            $listdetailCom[$i]= $generalexpenssesdetail[$i]->getDetailCommercial();
            $listdetailRec[$i]= $generalexpenssesdetail[$i]->getDetailRecherche();
         }
        $tvalist = $generalexpensses[0]->getTVAlist() ; 
        $tvalistpro = $generalexpensses[0]->getTvalistproduction();
        $tvalistcom = $generalexpensses[0]->getTvalistcommercial();
        $tvalistrec = $generalexpensses[0]->getTvalistrecherche();
        // unset($list[$name]); delete key from list
        //
        $status = '';
        $finaltva = [];
        /*$tva = $generalexpensses[0]->getTVAlist()[$name];
        $tvapro = $generalexpensses[0]->getTvalistproduction()[$name];
        $tvacom = $generalexpensses[0]->getTvalistcommercial()[$name];
        $tvarec = $generalexpensses[0]->getTvalistrecherche()[$name];*/
        
        if(array_key_exists($name, $tvalist) == true){
            $status = 'Administration';
            $finaltva = $generalexpensses[0]->getTVAlist()[$name];
        }
        
        if(array_key_exists($name, $tvalistpro) == true ){
            $status = 'Production';
            $finaltva = $generalexpensses[0]->getTvalistproduction()[$name];
        }
       
        if(array_key_exists($name, $tvalistcom) == true){
            $status = 'Commercial';
            $finaltva = $generalexpensses[0]->getTvalistcommercial()[$name]; 
        }
        if(array_key_exists($name, $tvalistrec) == true){
            $status = 'Recherche';
            $finaltva = $generalexpensses[0]->getTvalistrecherche()[$name];
            
        }
       
        
        //$finaltva = array_merge($tva,$tvapro,$tvacom,$tvarec) ;
        //dump($finaltva);die();
        $this->topicname = $name;
        if($status == 'Administration'){
            unset($list[$name]);
            unset($tvalist[$name]);
            $generalexpensses[0]->setAdministration($list); 
            $generalexpensses[0]->setTVAlist($tvalist) ; 
            for($i = 0 ; $i<$years;$i++){
               unset($listdetail[$i][$name]);
               $generalexpenssesdetail[$i]->setDetail($listdetail[$i]);
            }
           }
           if($status == 'Production'){
         
           unset($listpro[$name]);
           unset($tvalistpro[$name]); 
           $generalexpensses[0]->setProduction($listpro); 
           $generalexpensses[0]->setTvalistproduction($tvalistpro) ; 
           for($i = 0 ; $i<$years;$i++){
               unset($listdetailPro[$i][$name]);
               $generalexpenssesdetail[$i]->setDetailProduction($listdetailPro[$i]);
            }
       }
           if($status == 'Commercial'){
           
           unset($listcom[$name]);
           unset($tvalistcom[$name]);
           $generalexpensses[0]->setSales($listcom); 
           $generalexpensses[0]->setTvalistcommercial($tvalistcom) ; 

           for($i = 0 ; $i<$years;$i++){

               unset($listdetailCom[$i][$name]);
               $generalexpenssesdetail[$i]->setDetailCommercial($listdetailCom[$i]);
            }
       }
           if($status == 'Recherche'){
           
           unset($listrec[$name]);
           unset($tvalistrec[$name]); 
           $generalexpensses[0]->setRecherche($listrec); 
           $generalexpensses[0]->setTvalistrecherche($tvalistrec) ; 

           for($i = 0 ; $i<$years;$i++){
               
               unset($listdetailRec[$i][$name]);
               $generalexpenssesdetail[$i]->setDetailRecherche($listdetailRec[$i]);
            }
       }
       $entityManager->flush();
     
        return $this->redirectToRoute('generalexpenses');

       
    }

}