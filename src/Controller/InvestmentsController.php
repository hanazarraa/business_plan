<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\GeneralexpensesRepository;
use App\Form\InvestmentsFormType;
use App\Form\CollectionFormType;
use App\Entity\Investments;
use App\Entity\Investmentsdetail;
/**
* @Route("/{_locale}/dashboard/my-business-plan/Investments")
 */
class InvestmentsController extends AbstractController
{
     /**
     * @Route("/", name="investments")
     */
   public function index(Request $request){
    $businessSession =$this->container->get('session')->get('business');
    $entityManager = $this->getDoctrine()->getManager();
    $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
    $investmentsdetail = $entityManager->getRepository(Investmentsdetail::class)->findBy(['Investment' => $investments]);
    $tvalist =[];
    $duration =[]; 
    $categorie =[];
    $KeyAdmin = [];
    $KeyPro = [];
    $KeyCom  = [];
    $KeyRec  = [];
    $globalTotalpro =  [] ;
    $globalTotalcom =[];
    $globalTotalrec = [];
    $rangeofdetail = $businessSession->getRangeofdetail();
    if($investments!=[]){
    $tvalist = $investments[0]->getTvalist();
    $duration = $investments[0]->getDuration();
    $categorie = $investments[0]->getCategorie();
    $KeyAdmin = array_intersect_key($investments[0]->getTvalist(),$investmentsdetail[0]->getAdministration());
    $KeyPro   = array_intersect_key($investments[0]->getTvalist(),$investmentsdetail[0]->getProduction());
    $KeyCom   = array_intersect_key($investments[0]->getTvalist(),$investmentsdetail[0]->getSales());
    $KeyRec   = array_intersect_key($investments[0]->getTvalist(),$investmentsdetail[0]->getRecherche());
  }
    $years = $businessSession->getNumberofyears();
    //-----------------------------Calcul de somme-----------------------//
    $rangeofglobal = $years - $rangeofdetail;
    $globalTotal =[];
    $Sum=[];
    $Sumproduction=[];
    $Sumcommercial=[];
    $Sumrecherche=[];
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
  //-------------------------------Administration--------------------//
  if($investmentsdetail!=[]){
    for ($i=0 ; $i<$years ; $i++){
      foreach($investmentsdetail[$i]->getAdministration() as $key=>$list){
       $Sum[$i][$key] = array_sum($list);
      $total[$i] +=  $Sum[$i][$key];}}

      for ($i=0 ; $i<$rangeofglobal ; $i++){
        foreach($investments[0]->getAdministration() as $value){
         $globalTotal[$i] += $value[$i];}}
  //--------------------------------Fin Administration--------------//
  //--------------------------------Production---------------------//
  for ($i=0 ; $i<$years ; $i++){
    foreach($investmentsdetail[$i]->getProduction() as $key=>$list){
     $Sumproduction[$i][$key] = array_sum($list);
    $totalpro[$i] +=  $Sumproduction[$i][$key];}}
  for ($i=0 ; $i<$rangeofglobal ; $i++){
      foreach($investments[0]->getProduction() as $value){
       $globalTotalpro[$i] += $value[$i];}}
  //--------------------------------Fin Production----------------//
  //--------------------------------Commercial--------------------//
  for ($i=0 ; $i<$years ; $i++){
    foreach($investmentsdetail[$i]->getSales() as $key=>$list){
     $Sumcommercial[$i][$key] = array_sum($list);
    $totalcom[$i] +=  $Sumcommercial[$i][$key];}}
  for ($i=0 ; $i<$rangeofglobal ; $i++){
      foreach($investments[0]->getSales() as $value){
       $globalTotalcom[$i] += $value[$i];}}
  //--------------------------------Fin Commercial----------------//
  //--------------------------------Recherche---------------------//
  for ($i=0 ; $i<$years ; $i++){
    foreach($investmentsdetail[$i]->getRecherche() as $key=>$list){
     $Sumrecherche[$i][$key] = array_sum($list);
    $totalrech[$i] +=  $Sumrecherche[$i][$key];}}
  for ($i=0 ; $i<$rangeofglobal ; $i++){
      foreach($investments[0]->getRecherche() as $value){
       $globalTotalrec[$i] += $value[$i];}}
  //--------------------------------Fin Recherche-----------------//
 
  $form = $this->createForm(CollectionFormType::class,$investments[0]);
 
}
else{
  $form = $this->createForm(CollectionFormType::class);
 
}
  //-----------------------------Fin-----------------------------------//
    //dump($globalTotal);die();
  
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
     
      $entityManager->flush();
      return $this->redirectToRoute('investments');
    }
   
    return $this->render('Investments/index.html.twig',['business'=> $businessSession,'rangeofdetail'=>$rangeofdetail
    ,'form' => $form->createView() ,'tvalist'=> $tvalist,'duration' => $duration, 'categorie'=>$categorie,
    'Sum' => $Sum,'sumproduction'=>$Sumproduction,'sumrecherche'=>$Sumrecherche,'sumcommercial'=>$Sumcommercial,
    'total' => $total,'totalpro'=>$totalpro,'totalcom'=>$totalcom,'totalrec'=>$totalrech,
    'globalTotal'=> $globalTotal,'globalTotalpro'=>$globalTotalpro,'globalTotalcom'=>$globalTotalcom,'globalTotalrec'=>$globalTotalrec,
    'keyadmin'=>$KeyAdmin,'keypro' => $KeyPro , 'keycom' => $KeyCom , 'keyrec'=>$KeyRec,
    ]);
   }
    /**
     * @Route("-year-{id}", name="investmentsdetail")
     */
    public function detail(Request $request,$id){
      $businessSession =$this->container->get('session')->get('business');
      $entityManager = $this->getDoctrine()->getManager();
      $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
      $rangeofdetail = $businessSession->getRangeofdetail();
      $tvalist =[];
      $duration =[]; 
      $categorie =[];
      $totalsomme = 0;
      
      if($investments!=[]){
      $tvalist = $investments[0]->getTvalist();
      $duration = $investments[0]->getDuration();
      $categorie = $investments[0]->getCategorie();}
      $investmentsdetail = $entityManager->getRepository(Investmentsdetail::class)->findBy(['year' => $id ,'Investment' =>$investments] );
      //----------------------------Calcul de somme --------------------------//
      $total=0;
      $totalProduction=0 ;
      $totalCommercial=0;
      $totalRecherche=0;
      $Sum=[];
      $Sumproduction=[];
      $Sumcommercial=[];
      $Sumrecherche=[];
      for($x=0;$x<12;$x++){
        $totalpermonth[$x]= 0.00;
          $Sumpermonth [$x] = 0.00 ;
          $SumpermonthProduction[$x]=0.00 ;
          $SumpermonthCommercial[$x] = 0.00 ;
          $SumpermonthRecherche[$x] = 0.00;
      }
      if($investments!=[]){
          foreach($investmentsdetail[0]->getAdministration() as $key=>$list){//Somme par cle
           
      
           $Sum[$id][$key] = array_sum($list);
           $total += array_sum($list);
          }
          foreach($investmentsdetail[0]->getProduction() as $key=>$list){
           
      
              $Sumproduction[$id][$key] = array_sum($list);
              $totalProduction += array_sum($list);
              
             }
            
          foreach($investmentsdetail[0]->getSales() as $key=>$list){
           
      
              $Sumcommercial[$id][$key] = array_sum($list);
              $totalCommercial += array_sum($list);
             }
          foreach($investmentsdetail[0]->getRecherche() as $key=>$list){
           
      
              $Sumrecherche[$id][$key] = array_sum($list);
              $totalRecherche += array_sum($list);
             }

           for($x=0;$x<12;$x++){
          foreach($investmentsdetail[0]->getAdministration() as $key=>$list){//Somme par Mois
           $index =0 ;
      
              $Sumpermonth[$x] += $list[$x];
              
             }}
             for($x=0;$x<12;$x++){
              foreach($investmentsdetail[0]->getProduction() as $key=>$list){
               $index =0 ;
          
                  $SumpermonthProduction[$x] += $list[$x];
                  
                 }}
              for($x=0;$x<12;$x++){
              foreach($investmentsdetail[0]->getSales() as $key=>$list){
               $index =0 ;
          
                  $SumpermonthCommercial[$x] += $list[$x];
                  
                 }}
             for($x=0;$x<12;$x++){
                  foreach($investmentsdetail[0]->getRecherche() as $key=>$list){
                   $index =0 ;
                      $SumpermonthRecherche[$x] += $list[$x];
                      
             }}
            
              //--------------------------------Somme total-------------------//
             $totalsomme = $total + $totalProduction +  $totalCommercial + $totalRecherche;
             for($i=0 ; $i<12;$i++){
             $totalpermonth[$i]= $Sumpermonth[$i] + $SumpermonthProduction[$i] + $SumpermonthCommercial[$i]+$SumpermonthRecherche[$i];}
  //--------------------------------Fin de Somme total------------//
       //dump($total);die();
      //----------------------------Fin de Calcul ----------------------------//

      $form = $this->createForm(CollectionFormType::class,$investmentsdetail[0]);
    }
    else{
      $form = $this->createForm(CollectionFormType::class);
    }
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
     
        $entityManager->flush();
        return $this->redirectToRoute('investments');
      }
      
      return $this->render('Investments/detail.html.twig',['business'=> $businessSession , 'id' => $id,'rangeofdetail' => $rangeofdetail
      ,'form' => $form->createView() , 'tvalist'=>$tvalist , 'duration'=> $duration ,'categorie'=> $categorie
      ,'sum'=> $Sum,'sumproduction'=> $Sumproduction,'sumcommercial'=>$Sumcommercial,'sumrecherche'=>$Sumrecherche, 
      'total' => $total , 'totalProduction' => $totalProduction , 'totalCommercial' =>$totalCommercial,'totalRecherche'=>$totalRecherche,
      'sumpermonth' => $Sumpermonth,'sumpermonthproduction'=>$SumpermonthProduction,'sumpermonthcommercial'=>$SumpermonthCommercial,'sumpermonthrecherche'=>$SumpermonthRecherche
      ,'totalsomme'=>$totalsomme, 'totalpermonth'=>$totalpermonth,
      ]);

    }
     /**
     * @Route("/add", name="investmentsadd")
     */
    public function add(Request $request){
      $entityManager = $this->getDoctrine()->getManager();
      $empty = false;
      $businessSession =$this->container->get('session')->get('business');
      $rangeofdetail = $businessSession->getRangeofdetail();
      $years = $businessSession->getNumberofyears();
      $rangeofglobal = $years - $rangeofdetail;
      $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
      $investmentsdetail = $entityManager->getRepository(Investmentsdetail::class)->findBy(['Investment' =>$investments] );
      $Administration = [];
      $Production = [];
      $Commercial = [];
      $Recherche = [];
      if($investments==null && $investmentsdetail == null){
        $empty= true ;
      $investments = new Investments();
      $investmentsdetail = new Investmentsdetail();
      }
      else{
        
        $Administration = $investments[0]->getAdministration() ;  
        $Production = $investments[0]->getProduction() ;
        $Commercial = $investments[0]->getSales() ;
        $Recherche = $investments[0]->getRecherche() ;
        $TVAList = $investments[0]->getTvalist();
        $DurationList = $investments[0]->getDuration();
        $CategorieList = $investments[0]->getCategorie();
        for($i=0; $i<$years;$i++){
          $Administrationdetail[$i] = $investmentsdetail[$i]->getAdministration();
          $Productiondetail[$i] =  $investmentsdetail[$i]->getProduction();
          $Commercialdetail[$i] =  $investmentsdetail[$i]->getSales();
          $Recherchedetail[$i] =  $investmentsdetail[$i]->getRecherche();
        }
      }
      //dump(array_key_exists ('ss',$TVAList));die();
      $years = $businessSession->getNumberofyears();
      
      for($i=0 ; $i<$years - 1 ; $i++ ){
      $list[$i] = 0.00;
      }
      
      $form = $this->createForm(InvestmentsFormType::class);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
       // $investments = $form->getData();
        
        //------------------------global saisie-----------------//
           if($form->getData()['Department']==0 ){
            $TVAList[$form->getData()['Name']][0] = $form->getData()['VAT'];
            $DurationList[$form->getData()['Name']][0] = $form->getData()['Duration'];
            $CategorieList[$form->getData()['Name']][0] = $form->getData()['categorie'];
           for($i =0 ;$i<$rangeofglobal;$i++){
             $Administration[$form->getData()['Name']][$i] = "0.00";
           }
           for($i=0; $i<$years;$i++){
           for($x=0 ; $x<12;$x++){
            $Administrationdetail[$i][$form->getData()['Name']][$x] = "0.00"; 
            }}
           if($empty == true ){
            $investments->setAdministration($Administration);
            $investments->setTvalist($TVAList);
            $investments->setDuration($DurationList);
            $investments->setCategorie($CategorieList);
            $investments->setBusinessplan($businessSession);
            $entityManager->merge($investments);
            $entityManager->flush();
            //dump($investments);die();
            $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
            for($i=0; $i<$years;$i++){  
              //dump($investments);die();
              $investmentsdetail->setYear($i);
              $investmentsdetail->setAdministration($Administrationdetail[$i]);
              $investmentsdetail->setInvestment($investments[0]);
              $entityManager->merge($investmentsdetail);
              
              }
           
           }
           else{
          
           
           $investments[0]->setAdministration($Administration);
          
           $investments[0]->setTvalist($TVAList);
           $investments[0]->setDuration($DurationList);
           $investments[0]->setCategorie($CategorieList);
           for($i=0; $i<$years;$i++){
           $investmentsdetail[$i]->setAdministration($Administrationdetail[$i]);}
               
           //$investments[0]->setBusinessplan($businessSession);
           }

          }
          else if($form->getData()['Department']==1 ){
            $TVAList[$form->getData()['Name']][0] = $form->getData()['VAT'];
              $DurationList[$form->getData()['Name']][0] = $form->getData()['Duration'];
              $CategorieList[$form->getData()['Name']][0] = $form->getData()['categorie'];
            for($i =0 ;$i<$rangeofglobal;$i++){
              $Production[$form->getData()['Name']][$i] = "0.00";
              
            }
            for($i=0; $i<$years;$i++){
            for($x=0 ; $x<12;$x++){
             $Productiondetail[$i][$form->getData()['Name']][$x] = "0.00"; 
             }}
            if($empty== true){
             $investments->setProduction($Production);
             $investments->setTvalist($TVAList);
             $investments->setDuration($DurationList);
             $investments->setCategorie($CategorieList);
             $investments->setBusinessplan($businessSession);
             $entityManager->merge($investments);
             $entityManager->flush();
            //dump($investments);die();
            $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
            for($i=0; $i<$years;$i++){  
              //dump($investments);die();
              $investmentsdetail->setYear($i);
              $investmentsdetail->setProduction($Productiondetail[$i]);
              $investmentsdetail->setInvestment($investments[0]);
              $entityManager->merge($investmentsdetail);
              
              }
            }
            else{
          
            //dump($investments);die();
            $investments[0]->setProduction($Production);
            $investments[0]->setTvalist($TVAList);
            $investments[0]->setDuration($DurationList);
            $investments[0]->setCategorie($CategorieList);
            for($i=0; $i<$years;$i++){
            $investmentsdetail[$i]->setProduction($Productiondetail[$i]);}
            //$investments[0]->setBusinessplan($businessSession);
            }
          }
          else if($form->getData()['Department']==2 ){
            $TVAList[$form->getData()['Name']][0] = $form->getData()['VAT'];
            $DurationList[$form->getData()['Name']][0] = $form->getData()['Duration'];
            $CategorieList[$form->getData()['Name']][0] = $form->getData()['categorie'];
            for($i =0 ;$i<$rangeofglobal;$i++){
              $Commercial[$form->getData()['Name']][$i] = "0.00";
            
            }
            for($i=0; $i<$years;$i++){
            for($x=0 ; $x<12;$x++){
             $Commercialdetail[$i][$form->getData()['Name']][$x] = "0.00"; 
             }}
            if($empty== true){
             $investments->setSales($Commercial);
             $investments->setTvalist($TVAList);
             $investments->setDuration($DurationList);
             $investments->setCategorie($CategorieList);
             $investments->setBusinessplan($businessSession);
             $entityManager->merge($investments);
             $entityManager->flush();
            //dump($investments);die();
            $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
              for($i=0; $i<$years;$i++){  
              //dump($investments);die();
              $investmentsdetail->setYear($i);
              $investmentsdetail->setSales($Commercialdetail[$i]);
              $investmentsdetail->setInvestment($investments[0]);
              $entityManager->merge($investmentsdetail);
              
              }
            }
            else{
          
            //dump($investments);die();
            $investments[0]->setSales($Commercial);
            $investments[0]->setTvalist($TVAList);
            $investments[0]->setDuration($DurationList);
            $investments[0]->setCategorie($CategorieList);
            for($i=0; $i<$years;$i++){
            $investmentsdetail[$i]->setSales($Commercialdetail[$i]);}
            //$investments[0]->setBusinessplan($businessSession);
            }
          }
          else if($form->getData()['Department']==3 ){
            $TVAList[$form->getData()['Name']][0] = $form->getData()['VAT'];
            $DurationList[$form->getData()['Name']][0] = $form->getData()['Duration'];
            $CategorieList[$form->getData()['Name']][0] = $form->getData()['categorie'];
            for($i =0 ;$i<$rangeofglobal;$i++){
              $Recherche[$form->getData()['Name']][$i] = "0.00";
            
            }
            for($i=0; $i<$years;$i++){
            for($x=0 ; $x<12;$x++){
             $Recherchedetail[$i][$form->getData()['Name']][$x] = "0.00"; 
             }}
            if($empty== true){
             $investments->setRecherche($Recherche);
             $investments->setTvalist($TVAList);
             $investments->setDuration($DurationList);
             $investments->setCategorie($CategorieList);
             $investments->setBusinessplan($businessSession);
             $entityManager->merge($investments);
             $entityManager->flush();
            //dump($investments);die();
             $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
              for($i=0; $i<$years;$i++){  
              //dump($investments);die();
              $investmentsdetail->setYear($i);
              $investmentsdetail->setRecherche($Recherchedetail[$i]);
              $investmentsdetail->setInvestment($investments[0]);
              $entityManager->merge($investmentsdetail);
              
              }
            }
            else{
          
            //dump($investments);die();
            $investments[0]->setRecherche($Recherche);
            $investments[0]->setTvalist($TVAList);
            $investments[0]->setDuration($DurationList);
            $investments[0]->setCategorie($CategorieList);
            for($i=0; $i<$years;$i++){
            $investmentsdetail[$i]->setRecherche($Recherchedetail[$i]);}
            //$investments[0]->setBusinessplan($businessSession);
            }
          }
        //------------------------fin global--------------------//
        //------------------------Detailled saisie--------------//
    
        
        //------------------------fin detailled-----------------//
        if($empty ==true){
        
       
      }
        $entityManager->flush();
        return $this->redirectToRoute('investments');  
      }
      return $this->render('Investments/create.html.twig',['business'=> $businessSession,
      'form' => $form->createView() 
      ]);

    }

}