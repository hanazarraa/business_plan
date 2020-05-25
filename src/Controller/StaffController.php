<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Staff;
use App\Entity\Staffdetail;
use App\Entity\Product;
use App\Form\StaffFormType;
use App\Form\StaffCreateFormType;
use App\Form\StaffEditFormType;
use App\Form\CollectionFormType;
use App\Form\ChargesFormType;
use App\Entity\Sales;
use App\Entity\Salesdetailled;
use App\Controller\SalesController;
use App\Repository\SalesdetailledRepository;
use App\Repository\SalesRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
/**
* @Route("/{_locale}/dashboard/my-business-plan/Staff")
 */
class StaffController extends AbstractController
{
    private $ETP =[];
    private $ETPpro =[];
    private $ETPcom =[];
    private $ETPrec =[];
    private $salairebrut = [];
    private $salairebrutpro = [];
    private $salairebrutcom = [];
    private $salairebrutrec = [];
    private $SumcommisionAdm =[];
    private $SumcommisionPro = [];
    private $SumcommisionCom = [];
    private $SumcommisionRec = [];
    /**
     * @Route("/", name="staff")
     */
    public function index(Request $request,ProductRepository $productRepository,SalesRepository $SalesRepository)
    {

        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
        $staffdetail = $entityManager->getRepository(Staffdetail::class)->findBy(["staff" => $staff]);
        $keyadmin = [];
        $keypro = [];
        $keycom = [];
        $keyrec = [];
        $TotalETPAdm =[];
        $TotalETPPro =[];
        $TotalETPCom =[];
        $TotalETPRec =[];
        $ETPglobal = [];
        $ETPglobalpro = [];
        $ETPglobalcom =[];
        $ETPglobalrec = [];
        $coutannuel =[];
        $coutannuelpro =[];
        $coutannuelcom =[];
        $coutannuelrec =[];
        $TotalcoutannuelAdm=[];
        $TotalcoutannuelPro=[];
        $TotalcoutannuelCom =[];
        $TotalcoutannuelRec =[];
        $newETPdetailled=[];
        $newETPprodetailled=[];
        $newETPcomdetailled = [];
        $newETPrecdetailled =[];
        if($staffdetail != null){
        $keyadmin = array_keys($staffdetail[0]->getAdministration());  //fomre cle -> valeur(nom) utilisé pour l'affichage seulement
        $keypro = array_keys($staffdetail[0]->getProduction());
        $keycom = array_keys($staffdetail[0]->getSales());
        $keyrec = array_keys($staffdetail[0]->getRecherche());
        $adminstrationkeys =  $staffdetail[0]->getAdministration() ; // pour l'intersection entre les salire brut et l'adminisration
        $poductionkeys =  $staffdetail[0]->getProduction();
        $commercialkeys =  $staffdetail[0]->getSales();
        $recherchekeys =  $staffdetail[0]->getRecherche();
        $ETPglobal = $staff[0]->getAdministration();
        $ETPglobalpro = $staff[0]->getProduction();
        $ETPglobalcom = $staff[0]->getSales();
        $ETPglobalrec = $staff[0]->getRecherche();
        $this->salairebrut = array_intersect_key($staff[0]->getSalairebrut(),$adminstrationkeys);// contient toutes les categorie
        $this->salairebrutpro = array_intersect_key($staff[0]->getSalairebrut(),$poductionkeys);
        $this->salairebrutcom = array_intersect_key($staff[0]->getSalairebrut(),$commercialkeys);
        $this->salairebrutrec = array_intersect_key($staff[0]->getSalairebrut(),$recherchekeys);
        $charges =   $staff[0]->getCharges();
        $conditions = $staff[0]->getConditions();
        }
        $years = $businessSession->getNumberofyears();
        for($i=0 ; $i <$years ;$i++){
          $Totalsalairebrut[$i] = "0.00";
          $Totalchargepatronale[$i] = "0.00";
        }
        $rangeofdetail = $businessSession->getRangeofdetail();
        
        $rangeofglobal = $years - $rangeofdetail;
        $this->calculETP();
        //for($i =0 ; $i<$years;$i++){
       // $this->detail($request,$i,$productRepository,$SalesRepository);}
        foreach($this->ETP as $key=>$values){//Renverser la liste ETP avec saisie detailler
            foreach($values as $name=>$chiffre){
                $newETPdetailled[$name][$key] = $chiffre;
            }
        }
        foreach($this->ETPpro as $key=>$values){//Renverser la liste ETP avec saisie detailler
          foreach($values as $name=>$chiffre){
              $newETPprodetailled[$name][$key] = $chiffre;
          }
      }
      foreach($this->ETPcom as $key=>$values){//Renverser la liste ETP avec saisie detailler
        foreach($values as $name=>$chiffre){
            $newETPcomdetailled[$name][$key] = $chiffre;
        }
    }
    foreach($this->ETPrec as $key=>$values){//Renverser la liste ETP avec saisie detailler
      foreach($values as $name=>$chiffre){
          $newETPrecdetailled[$name][$key] = $chiffre;
      }
  }
       foreach($newETPdetailled as $key=>$value){ // merger les liste ETP globale et detailler
           for($i = $rangeofdetail ; $i<$years;$i++){
              
            $value[$i]= $ETPglobal[$key][$i-$rangeofdetail];
            $newETPdetailled[$key][$i] = $value[$i];
           }    
       }
       foreach($newETPprodetailled as $key=>$value){ // merger les liste ETP globale et detailler
        for($i = $rangeofdetail ; $i<$years;$i++){
           
         $value[$i]= $ETPglobalpro[$key][$i-$rangeofdetail];
         $newETPprodetailled[$key][$i] = $value[$i];
        }    
    }
    foreach($newETPcomdetailled as $key=>$value){ // merger les liste ETP globale et detailler
      for($i = $rangeofdetail ; $i<$years;$i++){
         
       $value[$i]= $ETPglobalcom[$key][$i-$rangeofdetail];
       $newETPcomdetailled[$key][$i] = $value[$i];
        }    
      }
      foreach($newETPrecdetailled as $key=>$value){ // merger les liste ETP globale et detailler
        for($i = $rangeofdetail ; $i<$years;$i++){
           
         $value[$i]= $ETPglobalrec[$key][$i-$rangeofdetail];
         $newETPrecdetailled[$key][$i] = $value[$i];
          }    
        }
        $this->calculCommision($businessSession,$entityManager,$staff,$staffdetail,$request,$productRepository,$SalesRepository);
        if($staffdetail != null){
            foreach($this->salairebrut as $key=>$values){
            $tvapatronale = $charges[$key][0] ;
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "0" || $conditions[$key][1]=="false") ){
                $tvapatronale = 0 ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = 0 ;}
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;}
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "0" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;}
            for($i=0;$i<$years;$i++){
            $coutannuel[$key][$i] =  round((($newETPdetailled[$key][$i]*12) * $values[$i]) +(($newETPdetailled[$key][$i]*12) * $values[$i]) * $tvapatronale /100 ) + $this->SumcommisionAdm[$key][$i] ;
            $Totalsalairebrut[$i] +=   round((($newETPdetailled[$key][$i]*12) * $values[$i]));
            $Totalchargepatronale[$i] +=   round((($newETPdetailled[$key][$i]*12) * $values[$i]) * $tvapatronale /100) ;
          }}
            foreach($this->salairebrutpro as $key=>$values){
              $tvapatronale = $charges[$key][0] ;
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "0" || $conditions[$key][1]=="false") ){
                $tvapatronale = 0 ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = 0 ;}
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;}
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "0" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;}
            for($i=0;$i<$years;$i++){
            $coutannuelpro[$key][$i] =  round((($newETPprodetailled[$key][$i]*12) * $values[$i]) +(($newETPprodetailled[$key][$i]*12) * $values[$i]) * $tvapatronale /100 ) +  $this->SumcommisionPro[$key][$i];
            $Totalsalairebrut[$i] +=   round((($newETPprodetailled[$key][$i]*12) * $values[$i]));
            $Totalchargepatronale[$i] +=   round((($newETPprodetailled[$key][$i]*12) * $values[$i]) * $tvapatronale /100) ;
          }}
            foreach($this->salairebrutcom as $key=>$values){
              $tvapatronale = $charges[$key][0] ;
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "0" || $conditions[$key][1]=="false") ){
                $tvapatronale = 0 ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = 0 ;}
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;}
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "0" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;}
            for($i=0;$i<$years;$i++){
            $coutannuelcom[$key][$i] =  round((($newETPcomdetailled[$key][$i]*12) * $values[$i]) +(($newETPcomdetailled[$key][$i]*12) * $values[$i]) * $tvapatronale/100 ) +  $this->SumcommisionCom[$key][$i];
            $Totalsalairebrut[$i] +=   round((($newETPcomdetailled[$key][$i]*12) * $values[$i]));  
            $Totalchargepatronale[$i] +=   round((($newETPcomdetailled[$key][$i]*12) * $values[$i]) * $tvapatronale /100) ;
          }}
            foreach($this->salairebrutrec as $key=>$values){
              $tvapatronale = $charges[$key][0] ;
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "0" || $conditions[$key][1]=="false") ){
                $tvapatronale = 0 ;}
              if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = 0 ;}
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;}
              if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) &&  ($conditions[$key][2]== "0" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "1" || $conditions[$key][1]=="true") ){
                $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;}
              for($i=0;$i<$years;$i++){
              $coutannuelrec[$key][$i] =  round((($newETPrecdetailled[$key][$i]*12) * $values[$i]) +(($newETPrecdetailled[$key][$i]*12) * $values[$i]) * $tvapatronale /100 ) +  $this->SumcommisionRec[$key][$i];
              $Totalsalairebrut[$i] +=   round((($newETPrecdetailled[$key][$i]*12) * $values[$i]));  
              $Totalchargepatronale[$i] +=   round((($newETPrecdetailled[$key][$i]*12) * $values[$i]) * $tvapatronale /100) ;
            }}
 //--------------------------------SommeETPtotal et Coutannuel------------------------------//
      //insitialiser les listes
      for($i=0 ; $i <$years ;$i++){
        $TotalETPAdm[$i] = "0.00"; 
        $TotalcoutannuelAdm[$i] = "0.00";
        $TotalETPPro[$i] = "0.00";
        $TotalcoutannuelPro[$i] = "0.00"; 
        $TotalETPCom[$i] = "0.00";
        $TotalcoutannuelCom[$i] = "0.00"; 
        $TotalETPRec[$i] = "0.00";
        $TotalcoutannuelRec[$i] = "0.00"; 
        }
         
        foreach($keyadmin as $value){//insitialiser les listes
          for($i=0 ; $i <$years ;$i++){
          $TotalETPAdm[$i] += $newETPdetailled[$value][$i] ;
          $TotalcoutannuelAdm[$i]+= $coutannuel[$value][$i];}}
        foreach($keypro as $value){//insitialiser les listes
          for($i=0 ; $i <$years ;$i++){
          $TotalETPPro[$i] += $newETPprodetailled[$value][$i] ;
          $TotalcoutannuelPro[$i]+= $coutannuelpro[$value][$i];}}
        foreach($keycom as $value){//insitialiser les listes
          for($i=0 ; $i <$years ;$i++){
          $TotalETPCom[$i] += $newETPcomdetailled[$value][$i] ;
          $TotalcoutannuelCom[$i]+= $coutannuelcom[$value][$i];}}
        foreach($keyrec as $value){//insitialiser les listes
          for($i=0 ; $i <$years ;$i++){
          $TotalETPRec[$i] += $newETPrecdetailled[$value][$i] ;
          $TotalcoutannuelRec[$i]+= $coutannuelrec[$value][$i];}}
        
        //--------------------------------FinSomme------------------------------------//
        //-------------------------------TotalSalairebrut----------------------------//
        
        //-------------------------------FinTotalSalairebrut--------------------------//
        $form = $this->createForm(StaffFormType::class,$staff[0]);
        }
        else{
            $form = $this->createForm(StaffFormType::class);
        }
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
     
          $entityManager->flush();
          return $this->redirectToRoute('staff');
         }
        return $this->render('staff/index.html.twig',[
         'business'=> $businessSession,'form' => $form->createView(),'keyadmin' =>  $keyadmin,'keypro'=> $keypro , 'keycom'=> $keycom, 'keyrec' =>$keyrec, 
          'rangeofdetail'=>$rangeofdetail , 'rangeofglobal'=>$rangeofglobal,'ETP'=>$this->ETP,
        'coutannuel' => $coutannuel , 'coutannuelpro' => $coutannuelpro ,'coutannuelcom' => $coutannuelcom ,'coutannuelrec' => $coutannuelrec,
        'ETPpro' => $this->ETPpro, 'ETPcom' => $this->ETPcom ,'ETPrec' => $this->ETPrec ,'TotalETPAdm' => $TotalETPAdm,'TotalETPPro' => $TotalETPPro,
        'TotalETPCom' => $TotalETPCom,'TotalETPRec' => $TotalETPRec, 'Totalsalairebrut' => $Totalsalairebrut,'Totalchargepatronale' => $Totalchargepatronale,
        'TotalcoutannuelAdm' => $TotalcoutannuelAdm ,'TotalcoutannuelPro' => $TotalcoutannuelPro ,'TotalcoutannuelCom' => $TotalcoutannuelCom ,'TotalcoutannuelRec' => $TotalcoutannuelRec 
          ]);
    }
    public function calculETP(){
      $businessSession =$this->container->get('session')->get('business');
      $entityManager = $this->getDoctrine()->getManager();
      $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
      $staffdetail = $entityManager->getRepository(Staffdetail::class)->findBy(["staff" => $staff]);
      $years = $businessSession->getNumberofyears();
      if($staffdetail != []){
      for($i =0 ; $i<$years;$i++){
        foreach($staffdetail[$i]->getAdministration() as $key=>$value ){
            $this->ETP[$i][$key] = round(array_sum($value)/12 , 2);}
        foreach($staffdetail[$i]->getProduction() as $key=>$value ){
            $this->ETPpro[$i][$key] = round(array_sum($value)/12 , 2);}
        foreach($staffdetail[$i]->getSales() as $key=>$value ){
            $this->ETPcom[$i][$key] = round(array_sum($value)/12 , 2);}
        foreach($staffdetail[$i]->getRecherche() as $key=>$value ){
            $this->ETPrec[$i][$key] = round(array_sum($value)/12 , 2);}
          }
        }    
    }
    //cette fonction est pour minimiser le temp d'excution lors de calcul de commission  et pour ajouter dans le cout annuel (cette fonction eviter de lire toute la fonction detail)
    public function calculCommision($businessSession,$entityManager,$staff,$staffdetail,Request $request,ProductRepository $productRepository,SalesRepository $SalesRepository){
      $years = $businessSession->getNumberofyears();
      $Tvaparproduit = [];
      $ListCommission =[];
      if($staffdetail != null){
        $commissionproduct = $staff[0]->getCommissionproduit();
        $conditions = $staff[0]->getConditions();
    
      foreach($staffdetail[0]->getAdministration() as $key=>$value){
        for($x = 0 ; $x < $years +1 ; $x++){    
          $this->SumcommisionAdm[$key][$x] = "0.00" ;
          for($i = 0 ; $i < 12 ; $i++){ $commisionAdm[$key][$x][$i] = "0.00" ;
          }}
      }
      foreach($staffdetail[0]->getProduction() as $key=>$value){
        for($x = 0 ; $x < $years +1 ; $x++){    
          $this->SumcommisionPro[$key][$x] = "0.00" ;
          for($i = 0 ; $i < 12 ; $i++){  $commisionPro[$key][$x][$i] = "0.00"  ;
          }}
      }
       
      foreach($staffdetail[0]->getSales() as $key=>$value){
        for($x = 0 ; $x < $years +1 ; $x++){
          $this->SumcommisionCom[$key][$x] = "0.00" ;    
          for($i = 0 ; $i < 12 ; $i++){   $commisionCom[$key][$x][$i] = "0.00" ; ;
          }}
      } 
      foreach($staffdetail[0]->getRecherche() as $key=>$value){
        for($x = 0 ; $x < $years +1 ; $x++){
          $this->SumcommisionRec[$key][$x] = "0.00" ;    
          for($i = 0 ; $i < 12 ; $i++){  $commisionRec[$key][$x][$i] = "0.00"  ;
          }}
      } 
      
    }
     
        $salesdetail = $this->sales = $entityManager->getRepository(Salesdetailled::class)->findBy(['sales'=> $businessSession->getSales()->getId()]);
        $response = $this->forward('App\Controller\SalesController::sales', [
          'request'  => $request,
          'productRepository' => $productRepository,
          'SalesRepository' => $SalesRepository,
      ]);
      $finalCA = SalesController::getfinalca();
      for($x = 0 ; $x < $years ; $x++){
        for($i = 0 ; $i < 12 ; $i++){
          $SumfinalCAperMouth[$x][$i] =  0 ;
        }
       }   
     
     foreach($finalCA as $key=>$value){
       for($x = 0 ; $x < $years ; $x++){
        for($i = 0 ; $i < 12 ; $i++){
          $SumfinalCAperMouth[$x][$i] +=  $finalCA[$key][$x][$i] ;
        }
       }     
     }
     $Allproductnames = array_keys($finalCA);
      foreach($Allproductnames as $cle=>$name){
      foreach($commissionproduct as  $key=>$value){
        foreach($value as $commission){
          if(strpos($commission, $name) !== false){
            
            $ListCommission[$key][substr($commission, 0 , strlen($name) )]  = substr($commission, strlen($name) ) ;
          }
          //$ListCommission[$key] = 
        }
        
      }}  
    //-------------------------calcul commission------------------------------//
    if($staffdetail!= []){
    for($i =0 ; $i<$years;$i++){
      foreach($staffdetail[$i]->getAdministration() as $key=>$value){
         
         $tvapatronale = $staff[0]->getCharges()[$key][0];
         $chargesalariale = $staff[0]->getCharges()[$key][1];
         if(array_key_exists($key,$staff[0]->getPourcentageCA())){
         $tvaCA = $staff[0]->getPourcentageCA()[$key][0];}
         if(array_key_exists($key,$staff[0]->getTypecommission())){
         $typeCommision = $staff[0]->getTypecommission()[$key][0];}
         if(array_key_exists($key,$ListCommission)){
         $Tvaparproduit = $ListCommission[$key];}
         
         if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
           $tvapatronale = $staff[0]->getCharges()[$key][0];
           $chargesalariale = $staff[0]->getCharges()[$key][1];
         }
         if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "" || $conditions[$key][1] == "false" )){
           $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ; 
         }
         if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "1" || $conditions[$key][1] == "true" )){
           $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;
           $chargesalariale  = 0 ; 
         }
         if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true") && ($conditions[$key][0]== "" || $conditions[$key][0] =="false") ){
           $chargesalariale  = 0 ;
           $tvapatronale =  0 ;
         }
         if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" )  && ($conditions[$key][0]== "1" || $conditions[$key][0]  =="true") && ($conditions[$key][1]== "" || $conditions[$key][1]  =="false") ){
           $chargesalariale  = 0 ;
           $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ;  
         }
         if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
           $chargesalariale  = 0 ;
           $tvapatronale = 0 ;  
         }
         if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2]=="false") ){
           $chargesalariale  = 0 ;
           $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;  
         }
         if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
           $chargesalariale  = 0 ;
           $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ; 
         }
      
        // dump($tvapatronale);die();
        foreach($value as $position=>$chiffre){
          if($typeCommision == '1'){//si le typecommision de tye Chiffre d'affaire
            if($chiffre >0 ){
         $commisionAdm[$key][$i][$position]+= ($SumfinalCAperMouth[$i][$position] * $tvaCA)/100 + ((($SumfinalCAperMouth[$i][$position] * $tvaCA)/100)* $tvapatronale) /100 ;
         $this->SumcommisionAdm[$key][$i]+=$commisionAdm[$key][$i][$position];}
         else{
           $commisionAdm[$key][$i][$position]+= 0 ;
           $this->SumcommisionAdm[$key][$i]+=$commisionAdm[$key][$i][$position];
          }
        } 
        if($typeCommision == '2'){//si le typecommission de type Produit
         if($chiffre >0){
          foreach($Tvaparproduit as $nomproduit=>$valueproduit){
$commisionAdm[$key][$i][$position]+= ($finalCA[$nomproduit][$i][$position] * $valueproduit) /100 +   ((($finalCA[$nomproduit][$i][$position] * $valueproduit) /100)* $tvapatronale) /100;
$this->SumcommisionAdm[$key][$i]+=     $commisionAdm[$key][$i][$position];   
}}
        else{ $commisionAdm[$key][$i][$position]+= 0 ; $this->SumcommisionAdm[$key][$i]+=$commisionAdm[$key][$i][$position];  }
       }}}}
   //-------------------------------------------------------------------------//
   for($i =0 ; $i<$years;$i++){
    foreach($staffdetail[$i]->getProduction() as $key=>$value){
       
       $tvapatronale = $staff[0]->getCharges()[$key][0];
       $chargesalariale = $staff[0]->getCharges()[$key][1];
       if(array_key_exists($key,$staff[0]->getPourcentageCA())){
       $tvaCA = $staff[0]->getPourcentageCA()[$key][0];}
       if(array_key_exists($key,$staff[0]->getTypecommission())){
       $typeCommision = $staff[0]->getTypecommission()[$key][0];}
       if(array_key_exists($key,$ListCommission)){
       $Tvaparproduit = $ListCommission[$key];}
       
       if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
         $tvapatronale = $staff[0]->getCharges()[$key][0];
         $chargesalariale = $staff[0]->getCharges()[$key][1];
       }
       if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "" || $conditions[$key][1] == "false" )){
         $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ; 
       }
       if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "1" || $conditions[$key][1] == "true" )){
         $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;
         $chargesalariale  = 0 ; 
       }
       if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true") && ($conditions[$key][0]== "" || $conditions[$key][0] =="false") ){
         $chargesalariale  = 0 ;
         $tvapatronale =  0 ;
       }
       if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" )  && ($conditions[$key][0]== "1" || $conditions[$key][0]  =="true") && ($conditions[$key][1]== "" || $conditions[$key][1]  =="false") ){
         $chargesalariale  = 0 ;
         $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ;  
       }
       if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
         $chargesalariale  = 0 ;
         $tvapatronale = 0 ;  
       }
       if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2]=="false") ){
         $chargesalariale  = 0 ;
         $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;  
       }
       if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
         $chargesalariale  = 0 ;
         $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ; 
       }
    
      // dump($tvapatronale);die();
      foreach($value as $position=>$chiffre){
        if($typeCommision == '1'){//si le typecommision de tye Chiffre d'affaire
          if($chiffre >0 ){
       $commisionPro[$key][$i][$position]+= ($SumfinalCAperMouth[$i][$position] * $tvaCA)/100 + ((($SumfinalCAperMouth[$i][$position] * $tvaCA)/100)* $tvapatronale) /100 ;
       $this->SumcommisionPro[$key][$i]+=$commisionPro[$key][$i][$position];}
       else{
         $commisionPro[$key][$i][$position]+= 0 ;
         $this->SumcommisionPro[$key][$i]+=$commisionPro[$key][$i][$position];
       }
      } 
      if($typeCommision == '2'){//si le typecommission de type Produit
       if($chiffre >0){
        foreach($Tvaparproduit as $nomproduit=>$valueproduit){
$commisionPro[$key][$i][$position]+= ($finalCA[$nomproduit][$i][$position] * $valueproduit) /100 +   ((($finalCA[$nomproduit][$i][$position] * $valueproduit) /100)* $tvapatronale) /100;
$this->SumcommisionPro[$key][$i]+= $commisionPro[$key][$i][$position];   
}}
      else{ $commisionPro[$key][$i][$position]+= 0 ;$this->SumcommisionPro[$key][$i] += $commisionPro[$key][$i][$position]; }
     }}}}   
  //-------------------------------------------------------------------------//
  for($i =0 ; $i<$years;$i++){
    foreach($staffdetail[$i]->getSales() as $key=>$value){
       
       $tvapatronale = $staff[0]->getCharges()[$key][0];
       $chargesalariale = $staff[0]->getCharges()[$key][1];
       if(array_key_exists($key,$staff[0]->getPourcentageCA())){
       $tvaCA = $staff[0]->getPourcentageCA()[$key][0];}
       if(array_key_exists($key,$staff[0]->getTypecommission())){
       $typeCommision = $staff[0]->getTypecommission()[$key][0];}
       if(array_key_exists($key,$ListCommission)){
       $Tvaparproduit = $ListCommission[$key];}
       
       if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
         $tvapatronale = $staff[0]->getCharges()[$key][0];
         $chargesalariale = $staff[0]->getCharges()[$key][1];
       }
       if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "" || $conditions[$key][1] == "false" )){
         $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ; 
       }
       if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "1" || $conditions[$key][1] == "true" )){
         $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;
         $chargesalariale  = 0 ; 
       }
       if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true") && ($conditions[$key][0]== "" || $conditions[$key][0] =="false") ){
         $chargesalariale  = 0 ;
         $tvapatronale =  0 ;
       }
       if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" )  && ($conditions[$key][0]== "1" || $conditions[$key][0]  =="true") && ($conditions[$key][1]== "" || $conditions[$key][1]  =="false") ){
         $chargesalariale  = 0 ;
         $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ;  
       }
       if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
         $chargesalariale  = 0 ;
         $tvapatronale = 0 ;  
       }
       if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2]=="false") ){
         $chargesalariale  = 0 ;
         $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;  
       }
       if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
         $chargesalariale  = 0 ;
         $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ; 
       }
    
      // dump($tvapatronale);die();
      foreach($value as $position=>$chiffre){
        if($typeCommision == '1'){//si le typecommision de tye Chiffre d'affaire
          if($chiffre >0 ){
       $commisionCom[$key][$i][$position]+= ($SumfinalCAperMouth[$i][$position] * $tvaCA)/100 + ((($SumfinalCAperMouth[$i][$position] * $tvaCA)/100)* $tvapatronale) /100 ;
       $this->SumcommisionCom[$key][$i] +=$commisionCom[$key][$i][$position]; }
       else{
         $commisionCom[$key][$i][$position]+= 0 ;
         $this->SumcommisionCom[$key][$i] +=$commisionCom[$key][$i][$position] ;}
      } 
      if($typeCommision == '2'){//si le typecommission de type Produit
       if($chiffre >0){
        foreach($Tvaparproduit as $nomproduit=>$valueproduit){
$commisionCom[$key][$i][$position]+= ($finalCA[$nomproduit][$i][$position] * $valueproduit) /100 +   ((($finalCA[$nomproduit][$i][$position] * $valueproduit) /100)* $tvapatronale) /100;
$this->SumcommisionCom[$key][$i]+=   $commisionCom[$key][$i][$position];   
}}
      else{ $commisionCom[$key][$i][$position]+= 0 ; $this->SumcommisionCom[$key][$i]+=$commisionCom[$key][$i][$position];}
     }}}}
     //-----------------------------------------------------------------------//
     for($i =0 ; $i<$years;$i++){
      foreach($staffdetail[$i]->getRecherche() as $key=>$value){
         
         $tvapatronale = $staff[0]->getCharges()[$key][0];
         $chargesalariale = $staff[0]->getCharges()[$key][1];
         if(array_key_exists($key,$staff[0]->getPourcentageCA())){
         $tvaCA = $staff[0]->getPourcentageCA()[$key][0];}
         if(array_key_exists($key,$staff[0]->getTypecommission())){
         $typeCommision = $staff[0]->getTypecommission()[$key][0];}
         if(array_key_exists($key,$ListCommission)){
         $Tvaparproduit = $ListCommission[$key];}
         
         if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
           $tvapatronale = $staff[0]->getCharges()[$key][0];
           $chargesalariale = $staff[0]->getCharges()[$key][1];
         }
         if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "" || $conditions[$key][1] == "false" )){
           $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ; 
         }
         if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "1" || $conditions[$key][1] == "true" )){
           $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;
           $chargesalariale  = 0 ; 
         }
         if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true") && ($conditions[$key][0]== "" || $conditions[$key][0] =="false") ){
           $chargesalariale  = 0 ;
           $tvapatronale =  0 ;
         }
         if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" )  && ($conditions[$key][0]== "1" || $conditions[$key][0]  =="true") && ($conditions[$key][1]== "" || $conditions[$key][1]  =="false") ){
           $chargesalariale  = 0 ;
           $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ;  
         }
         if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
           $chargesalariale  = 0 ;
           $tvapatronale = 0 ;  
         }
         if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2]=="false") ){
           $chargesalariale  = 0 ;
           $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;  
         }
         if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
           $chargesalariale  = 0 ;
           $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ; 
         }
      
        // dump($tvapatronale);die();
        foreach($value as $position=>$chiffre){
          if($typeCommision == '1'){//si le typecommision de tye Chiffre d'affaire
            if($chiffre >0 ){
         $commisionRec[$key][$i][$position]+= ($SumfinalCAperMouth[$i][$position] * $tvaCA)/100 + ((($SumfinalCAperMouth[$i][$position] * $tvaCA)/100)* $tvapatronale) /100 ;
         $this->SumcommisionRec[$key][$i]+=$commisionRec[$key][$i][$position];}
         else{
           $commisionRec[$key][$i][$position]+= 0 ; $this->SumcommisionRec[$key][$i]+=$commisionRec[$key][$i][$position];
         }
        } 
        if($typeCommision == '2'){//si le typecommission de type Produit
         if($chiffre >0){
          foreach($Tvaparproduit as $nomproduit=>$valueproduit){
$commisionRec[$key][$i][$position]+= ($finalCA[$nomproduit][$i][$position] * $valueproduit) /100 +   ((($finalCA[$nomproduit][$i][$position] * $valueproduit) /100)* $tvapatronale) /100;
$this->SumcommisionRec[$key][$i] +=     $commisionRec[$key][$i][$position];    
}}
        else{ $commisionRec[$key][$i][$position]+= 0 ;$this->SumcommisionRec[$key][$i] +=$commisionRec[$key][$i][$position]; }
       }}}}
    //dump($commisionPro,$SumcommisionPro);die();  
   //----------------------------fin de calcul-------------------------------//
   //----------------------------Somme commission----------------------------//
   //----------------------------Fin Somme ----------------------------------// 
  }

    }

     /**
     * @Route("-year-{id}", name="staffdetail")
     */
    public function detail(Request $request,$id,ProductRepository $productRepository,SalesRepository $SalesRepository){
        $businessSession =$this->container->get('session')->get('business');
        
        $entityManager = $this->getDoctrine()->getManager();
        $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
        $staffdetail = $entityManager->getRepository(Staffdetail::class)->findBy(["staff" => $staff]);
        $years = $businessSession->getNumberofyears();
        $keyadmin = [];
        $keypro = [];
        $keycom = [];
        $keyrec = [];
        $Tvaparproduit = [];
        $salairebrut = [];
        $ListCommission =[];
        if($staffdetail != null){
          $keyadmin = array_keys($staffdetail[0]->getAdministration());
          $keypro = array_keys($staffdetail[0]->getProduction());
          $keycom = array_keys($staffdetail[0]->getSales());
          $keyrec = array_keys($staffdetail[0]->getRecherche());
          $conditions = $staff[0]->getConditions();
          $commissionproduct = $staff[0]->getCommissionproduit();
        }
        //-----------------------------Partie pour le tableau sales ------------------------------//
        $salesdetail = $this->sales = $entityManager->getRepository(Salesdetailled::class)->findBy(['sales'=> $businessSession->getSales()->getId()]);
        $response = $this->forward('App\Controller\SalesController::sales', [
          'request'  => $request,
          'productRepository' => $productRepository,
          'SalesRepository' => $SalesRepository,
      ]);
      $finalCA = SalesController::getfinalca();
      //------------------------Calcul par CA -------------------------//
        for($x = 0 ; $x < $years ; $x++){
         for($i = 0 ; $i < 12 ; $i++){
           $SumfinalCAperMouth[$x][$i] =  0 ;
         }
        }   
      
      foreach($finalCA as $key=>$value){
        for($x = 0 ; $x < $years ; $x++){
         for($i = 0 ; $i < 12 ; $i++){
           $SumfinalCAperMouth[$x][$i] +=  $finalCA[$key][$x][$i] ;
         }
        }     
      }
      //-------------------------Fin de Calcul -----------------------//
      // ----------------------- Calclul par produit -----------------//
      $Allproductnames = array_keys($finalCA);
      foreach($Allproductnames as $cle=>$name){
      foreach($commissionproduct as  $key=>$value){
        foreach($value as $commission){
          if(strpos($commission, $name) !== false){
            
            $ListCommission[$key][substr($commission, 0 , strlen($name) )]  = substr($commission, strlen($name) ) ;
          }
          //$ListCommission[$key] = 
        }
        
      }}  
     //dump($ListCommission);die();
      //------------------------fin de Calcul----------------------------//
      //dump($commissionproduct,$ListCommission);die();
  
        //-----------------------------Fin----------------------------------------------------------//
        //-----------------------------instantier les listes  pour le decaissement-------------------------//
        
       
          for($x = 0 ; $x < $years +1 ; $x++){    
        for($i = 0 ; $i < 12 ; $i++){
        $commisionAdm[$x][$i] = "0.00" ;
        $commisionPro[$x][$i] = "0.00" ;
        $commisionCom[$x][$i] = "0.00" ;
        $commisionRec[$x][$i] = "0.00" ;

        $totalsalairbrutAdm[$x][$i] = "0.00";
        $totalsalairbrutPro[$x][$i] = "0.00";
        $totalsalairbrutCom[$x][$i] = "0.00";
        $totalsalairbrutRec[$x][$i] = "0.00";

        $couttotalpersonemAdm[$x][$i] = "0.00";
        $couttotalpersonemPro[$x][$i] = "0.00";
        $couttotalpersonemCom[$x][$i] = "0.00";
        $couttotalpersonemRec[$x][$i] = "0.00";

        $NetapayerAdm[$x][$i] = "0.00";
        $NetapayerPro[$x][$i] = "0.00";
        $NetapayerCom[$x][$i] = "0.00";
        $NetapayerRec[$x][$i] = "0.00";

        $TotalDecaissementAdm[$x][$i] = "0.00";
        $TotalDecaissementPro[$x][$i] = "0.00";
        $TotalDecaissementCom[$x][$i] = "0.00";
        $TotalDecaissementRec[$x][$i] = "0.00";

        $lastDecchargeemployeurhorsgerantAdm[$x][$i] ="0.00";
        $lastDecchargeemployeurhorsgerantPro[$x][$i] ="0.00";
        $lastDecchargeemployeurhorsgerantCom[$x][$i] ="0.00";
        $lastDecchargeemployeurhorsgerantRec[$x][$i] ="0.00";

        $lastDecchargesalarialesAdm[$x][$i] = "0.00";
        $lastDecchargesalarialesPro[$x][$i] = "0.00";
        $lastDecchargesalarialesCom[$x][$i] = "0.00";
        $lastDecchargesalarialesRec[$x][$i] = "0.00";

        $lastDecchargesAdm[$x][$i] = "0.00";
        $lastDecchargesPro[$x][$i] = "0.00";
        $lastDecchargesCom[$x][$i] = "0.00";
        $lastDecchargesRec[$x][$i] = "0.00";
      }}
     
       
         
      for($x = 0 ; $x < $years +1 ; $x++){ 
        for($i =0 ; $i<13 ; $i++){
          $DecchargegerantnonsalarieAdm[$x][$i]="0.00";
          $DecchargegerantnonsalariePro[$x][$i]="0.00";
          $DecchargegerantnonsalarieCom[$x][$i]="0.00";
          $DecchargegerantnonsalarieRec[$x][$i]="0.00";

          $DecchargeemployeurhorsgerantAdm[$x][$i] = "0.00";
          $DecchargeemployeurhorsgerantPro[$x][$i] = "0.00";
          $DecchargeemployeurhorsgerantCom[$x][$i] = "0.00";
          $DecchargeemployeurhorsgerantRec[$x][$i] = "0.00";

          $DecchargesalarialesAdm[$x][$i] = "0.00";
          $DecchargesalarialesPro[$x][$i] = "0.00";
          $DecchargesalarialesCom[$x][$i] = "0.00";
          $DecchargesalarialesRec[$x][$i] = "0.00";

          $DecchargesAdm[$x][$i] = "0.00";
          $DecchargesPro[$x][$i] = "0.00";
          $DecchargesCom[$x][$i] = "0.00";
          $DecchargesRec[$x][$i] = "0.00";
        }}
        for($i =0 ; $i<$years;$i++){
          for($x=0;$x<12;$x++){
            $TOTAL[$i][$x] = "0.00";
          }}
        //-------------------------------Fin-----------------------------------------------//
        $years = $businessSession->getNumberofyears();
        if($staffdetail != null){
        $keyadmin = array_keys($staffdetail[0]->getAdministration());
        
       //fonction pour le calcul ETP
       $this->calculETP();
          
        $salairebrut = $staff[0]->getSalairebrut();
       //----------------Debut de calcul de decaissement ----------------------------//
       for($i =0 ; $i<$years;$i++){
       foreach($staffdetail[$i]->getAdministration() as $key=>$value){
          
          $tvapatronale = $staff[0]->getCharges()[$key][0];
          $chargesalariale = $staff[0]->getCharges()[$key][1];
          if(array_key_exists($key,$staff[0]->getPourcentageCA())){
          $tvaCA = $staff[0]->getPourcentageCA()[$key][0];}
          if(array_key_exists($key,$staff[0]->getTypecommission())){
          $typeCommision = $staff[0]->getTypecommission()[$key][0];}
          if(array_key_exists($key,$ListCommission)){
          $Tvaparproduit = $ListCommission[$key];}
          
          if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
            $tvapatronale = $staff[0]->getCharges()[$key][0];
            $chargesalariale = $staff[0]->getCharges()[$key][1];
          }
          if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "" || $conditions[$key][1] == "false" )){
            $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ; 
          }
          if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "1" || $conditions[$key][1] == "true" )){
            $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;
            $chargesalariale  = 0 ; 
          }
          if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true") && ($conditions[$key][0]== "" || $conditions[$key][0] =="false") ){
            $chargesalariale  = 0 ;
            $tvapatronale =  0 ;
          }
          if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" )  && ($conditions[$key][0]== "1" || $conditions[$key][0]  =="true") && ($conditions[$key][1]== "" || $conditions[$key][1]  =="false") ){
            $chargesalariale  = 0 ;
            $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ;  
          }
          if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
            $chargesalariale  = 0 ;
            $tvapatronale = 0 ;  
          }
          if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2]=="false") ){
            $chargesalariale  = 0 ;
            $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;  
          }
          if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
            $chargesalariale  = 0 ;
            $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ; 
          }
       
         // dump($tvapatronale);die();
         foreach($value as $position=>$chiffre){
           if($typeCommision == '1'){//si le typecommision de tye Chiffre d'affaire
             if($chiffre >0 ){
          $commisionAdm[$i][$position]+= ($SumfinalCAperMouth[$i][$position] * $tvaCA)/100 + ((($SumfinalCAperMouth[$i][$position] * $tvaCA)/100)* $tvapatronale) /100 ;}
          else{
            $commisionAdm[$i][$position]+= 0 ;
          }
         } 
         if($typeCommision == '2'){//si le typecommission de type Produit
          if($chiffre >0){
           foreach($Tvaparproduit as $nomproduit=>$valueproduit){
$commisionAdm[$i][$position]+= ($finalCA[$nomproduit][$i][$position] * $valueproduit) /100 +   ((($finalCA[$nomproduit][$i][$position] * $valueproduit) /100)* $tvapatronale) /100;
         }}
         else{ $commisionAdm[$i][$position]+= 0 ;}
        }
          $totalsalairbrutAdm[$i][$position] += $chiffre * $salairebrut[$key][$i];
          $couttotalpersonemAdm[$i][$position]+= ($chiffre * $salairebrut[$key][$i] *$tvapatronale ) /100 + $chiffre * $salairebrut[$key][$i];
          $NetapayerAdm[$i][$position] += $chiffre * $salairebrut[$key][$i] - ($chiffre * $salairebrut[$key][$i] * $chargesalariale) /100 ;
          $DecchargesalarialesAdm[$i][$position+ 1] +=  ($chiffre * $salairebrut[$key][$i] * $chargesalariale) /100 ;
          //dump($staff[0]->getCharges()[$key][0]);die();
        }}}
        //dump($commisionAdm);die();
        for($i =0 ; $i<$years;$i++){
        for($x=0;$x<12;$x++){//calculer le decaissement mensuel
          
          $DecchargeemployeurhorsgerantAdm[$i][$x+ 1] = $couttotalpersonemAdm[$i][$x] - $totalsalairbrutAdm[$i][$x];
          
        }}
        for($i =0 ; $i<$years;$i++){
        for($x=0;$x<13;$x++){
          $DecchargesAdm[$i][$x] = $DecchargesalarialesAdm[$i][$x] + $DecchargeemployeurhorsgerantAdm[$i][$x];
        }}
       //decaissement total
        for($i =0 ; $i<$years;$i++){
        for($x=0;$x<13;$x++){
          if($x<12){
          $TotalDecaissementAdm[$i][$x] += $NetapayerAdm[$i][$x] + $DecchargesAdm[$i][$x] + $commisionAdm[$i][$x];}
          else{
            
            $TotalDecaissementAdm[$i+1][0] += $DecchargesAdm[$i][$x];
          }
        }}

        //reformuler les liste de decaissemnt 
        for($i =0 ; $i<$years;$i++){
          for($x=0;$x<13;$x++){
            if($x<12){
              $lastDecchargeemployeurhorsgerantAdm[$i][$x] += $DecchargeemployeurhorsgerantAdm[$i][$x];
              $lastDecchargesalarialesAdm[$i][$x] += $DecchargesalarialesAdm[$i][$x];
              $lastDecchargesAdm[$i][$x] += $DecchargesAdm[$i][$x];}
              else{
               
                $lastDecchargeemployeurhorsgerantAdm[$i+1][0] += $DecchargeemployeurhorsgerantAdm[$i][$x] ;
                $lastDecchargesalarialesAdm[$i+1][0] += $DecchargesalarialesAdm[$i][$x];
                $lastDecchargesAdm[$i+1][$i] += $DecchargesAdm[$i][$x];
              }
          }}
        //----------------------------------Production---------------------------------//
        for($i =0 ; $i<$years;$i++){
          foreach($staffdetail[$i]->getProduction() as $key=>$value){
             
             $tvapatronale = $staff[0]->getCharges()[$key][0];
             $chargesalariale = $staff[0]->getCharges()[$key][1];
             if(array_key_exists($key,$staff[0]->getPourcentageCA())){
             $tvaCA = $staff[0]->getPourcentageCA()[$key][0];}
             if(array_key_exists($key,$staff[0]->getTypecommission())){
             $typeCommision = $staff[0]->getTypecommission()[$key][0];}
             if(array_key_exists($key,$ListCommission)){
             $Tvaparproduit = $ListCommission[$key];}
             
             if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
               $tvapatronale = $staff[0]->getCharges()[$key][0];
               $chargesalariale = $staff[0]->getCharges()[$key][1];
             }
             if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "" || $conditions[$key][1] == "false" )){
               $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ; 
             }
             if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "1" || $conditions[$key][1] == "true" )){
               $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;
               $chargesalariale  = 0 ; 
             }
             if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true") && ($conditions[$key][0]== "" || $conditions[$key][0] =="false") ){
               $chargesalariale  = 0 ;
               $tvapatronale =  0 ;
             }
             if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" )  && ($conditions[$key][0]== "1" || $conditions[$key][0]  =="true") && ($conditions[$key][1]== "" || $conditions[$key][1]  =="false") ){
               $chargesalariale  = 0 ;
               $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ;  
             }
             if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
               $chargesalariale  = 0 ;
               $tvapatronale = 0 ;  
             }
             if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2]=="false") ){
               $chargesalariale  = 0 ;
               $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;  
             }
             if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
               $chargesalariale  = 0 ;
               $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ; 
             }
          
            // dump($tvapatronale);die();
            foreach($value as $position=>$chiffre){
              if($typeCommision == '1'){//si le typecommision de tye Chiffre d'affaire
                if($chiffre >0){
             $commisionPro[$i][$position]+= ($SumfinalCAperMouth[$i][$position] * $tvaCA)/100 + ((($SumfinalCAperMouth[$i][$position] * $tvaCA)/100)* $tvapatronale) /100 ;}
             else{
               $commisionPro[$i][$position]+= 0 ;
             }
            } 
            if($typeCommision == '2'){//si le typecommission de type Produit
             if($chiffre >0){
              foreach($Tvaparproduit as $nomproduit=>$valueproduit){
   $commisionPro[$i][$position]+= ($finalCA[$nomproduit][$i][$position] * $valueproduit) /100 +   ((($finalCA[$nomproduit][$i][$position] * $valueproduit) /100)* $tvapatronale) /100;
            }}
            else{ $commisionPro[$i][$position]+= 0 ;}
           }
             $totalsalairbrutPro[$i][$position] += $chiffre * $salairebrut[$key][$i];
             $couttotalpersonemPro[$i][$position]+= ($chiffre * $salairebrut[$key][$i] *$tvapatronale ) /100 + $chiffre * $salairebrut[$key][$i];
             $NetapayerPro[$i][$position] += $chiffre * $salairebrut[$key][$i] - ($chiffre * $salairebrut[$key][$i] * $chargesalariale) /100 ;
             $DecchargesalarialesPro[$i][$position+ 1] +=  ($chiffre * $salairebrut[$key][$i] * $chargesalariale) /100 ;
             //dump($staff[0]->getCharges()[$key][0]);die();
           }}}
           for($i =0 ; $i<$years;$i++){
            for($x=0;$x<12;$x++){//calculer le decaissement mensuel
              
              $DecchargeemployeurhorsgerantPro[$i][$x+ 1] = $couttotalpersonemPro[$i][$x] - $totalsalairbrutPro[$i][$x];
              
            }}
            for($i =0 ; $i<$years;$i++){
            for($x=0;$x<13;$x++){
              $DecchargesPro[$i][$x] = $DecchargesalarialesPro[$i][$x] + $DecchargeemployeurhorsgerantPro[$i][$x];
            }}
           //decaissement total
            for($i =0 ; $i<$years;$i++){
            for($x=0;$x<13;$x++){
              if($x<12){
              $TotalDecaissementPro[$i][$x] += $NetapayerPro[$i][$x] + $DecchargesPro[$i][$x] + $commisionPro[$i][$x];}
              else{
                
                $TotalDecaissementPro[$i+1][0] += $DecchargesPro[$i][$x];
              }
            }}
    
            //reformuler les liste de decaissemnt 
            for($i =0 ; $i<$years;$i++){
              for($x=0;$x<13;$x++){
                if($x<12){
                  $lastDecchargeemployeurhorsgerantPro[$i][$x] += $DecchargeemployeurhorsgerantPro[$i][$x];
                  $lastDecchargesalarialesPro[$i][$x] += $DecchargesalarialesPro[$i][$x];
                  $lastDecchargesPro[$i][$x] += $DecchargesPro[$i][$x];}
                  else{
                   
                    $lastDecchargeemployeurhorsgerantPro[$i+1][0] += $DecchargeemployeurhorsgerantPro[$i][$x] ;
                    $lastDecchargesalarialesPro[$i+1][0] += $DecchargesalarialesPro[$i][$x];
                    $lastDecchargesPro[$i+1][$i] += $DecchargesPro[$i][$x];
                  }
              }}
        //----------------------------------FinProduction--------------------------------//
       //------------------------------------Commercial----------------------------------//
       for($i =0 ; $i<$years;$i++){
        foreach($staffdetail[$i]->getSales() as $key=>$value){
           
           $tvapatronale = $staff[0]->getCharges()[$key][0];
           $chargesalariale = $staff[0]->getCharges()[$key][1];
           if(array_key_exists($key,$staff[0]->getPourcentageCA())){
           $tvaCA = $staff[0]->getPourcentageCA()[$key][0];}
           if(array_key_exists($key,$staff[0]->getTypecommission())){
           $typeCommision = $staff[0]->getTypecommission()[$key][0];}
           if(array_key_exists($key,$ListCommission)){
           $Tvaparproduit = $ListCommission[$key];}
           
           if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
             $tvapatronale = $staff[0]->getCharges()[$key][0];
             $chargesalariale = $staff[0]->getCharges()[$key][1];
           }
           if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "" || $conditions[$key][1] == "false" )){
             $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ; 
           }
           if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "1" || $conditions[$key][1] == "true" )){
             $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;
             $chargesalariale  = 0 ; 
           }
           if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true") && ($conditions[$key][0]== "" || $conditions[$key][0] =="false") ){
             $chargesalariale  = 0 ;
             $tvapatronale =  0 ;
           }
           if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" )  && ($conditions[$key][0]== "1" || $conditions[$key][0]  =="true") && ($conditions[$key][1]== "" || $conditions[$key][1]  =="false") ){
             $chargesalariale  = 0 ;
             $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ;  
           }
           if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
             $chargesalariale  = 0 ;
             $tvapatronale = 0 ;  
           }
           if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2]=="false") ){
             $chargesalariale  = 0 ;
             $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;  
           }
           if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
             $chargesalariale  = 0 ;
             $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ; 
           }
        
          // dump($tvapatronale);die();
          foreach($value as $position=>$chiffre){
            if($typeCommision == '1'){//si le typecommision de tye Chiffre d'affaire
              if($chiffre >0 ){
           $commisionCom[$i][$position]+= ($SumfinalCAperMouth[$i][$position] * $tvaCA)/100 + ((($SumfinalCAperMouth[$i][$position] * $tvaCA)/100)* $tvapatronale) /100 ;}
           else{
             $commisionCom[$i][$position]+= 0 ;
           }
          } 
          if($typeCommision == '2'){//si le typecommission de type Produit
           if($chiffre >0){
            foreach($Tvaparproduit as $nomproduit=>$valueproduit){
 $commisionCom[$i][$position]+= ($finalCA[$nomproduit][$i][$position] * $valueproduit) /100 +   ((($finalCA[$nomproduit][$i][$position] * $valueproduit) /100)* $tvapatronale) /100;
          }}
          else{ $commisionCom[$i][$position]+= 0 ;}
         }
           $totalsalairbrutCom[$i][$position] += $chiffre * $salairebrut[$key][$i];
           $couttotalpersonemCom[$i][$position]+= ($chiffre * $salairebrut[$key][$i] *$tvapatronale ) /100 + $chiffre * $salairebrut[$key][$i];
           $NetapayerCom[$i][$position] += $chiffre * $salairebrut[$key][$i] - ($chiffre * $salairebrut[$key][$i] * $chargesalariale) /100 ;
           $DecchargesalarialesCom[$i][$position+ 1] +=  ($chiffre * $salairebrut[$key][$i] * $chargesalariale) /100 ;
           //dump($staff[0]->getCharges()[$key][0]);die();
         }}}
         for($i =0 ; $i<$years;$i++){
          for($x=0;$x<12;$x++){//calculer le decaissement mensuel
            
            $DecchargeemployeurhorsgerantCom[$i][$x+ 1] = $couttotalpersonemCom[$i][$x] - $totalsalairbrutCom[$i][$x];
            
          }}
          for($i =0 ; $i<$years;$i++){
          for($x=0;$x<13;$x++){
            $DecchargesCom[$i][$x] = $DecchargesalarialesCom[$i][$x] + $DecchargeemployeurhorsgerantCom[$i][$x];
          }}
         //decaissement total
          for($i =0 ; $i<$years;$i++){
          for($x=0;$x<13;$x++){
            if($x<12){
            $TotalDecaissementCom[$i][$x] += $NetapayerCom[$i][$x] + $DecchargesCom[$i][$x] + $commisionCom[$i][$x];}
            else{
              
              $TotalDecaissementCom[$i+1][0] += $DecchargesCom[$i][$x];
            }
          }}
  
          //reformuler les liste de decaissemnt 
          for($i =0 ; $i<$years;$i++){
            for($x=0;$x<13;$x++){
              if($x<12){
                $lastDecchargeemployeurhorsgerantCom[$i][$x] += $DecchargeemployeurhorsgerantCom[$i][$x];
                $lastDecchargesalarialesCom[$i][$x] += $DecchargesalarialesCom[$i][$x];
                $lastDecchargesCom[$i][$x] += $DecchargesCom[$i][$x];}
                else{
                 
                  $lastDecchargeemployeurhorsgerantCom[$i+1][0] += $DecchargeemployeurhorsgerantCom[$i][$x] ;
                  $lastDecchargesalarialesCom[$i+1][0] += $DecchargesalarialesCom[$i][$x];
                  $lastDecchargesCom[$i+1][$i] += $DecchargesCom[$i][$x];
                }
            }}
       //-----------------------------------FinCommercial---------------------------------// 
       //-----------------------------------Recherche-------------------------------------//
       for($i =0 ; $i<$years;$i++){
        foreach($staffdetail[$i]->getRecherche() as $key=>$value){
           
           $tvapatronale = $staff[0]->getCharges()[$key][0];
           $chargesalariale = $staff[0]->getCharges()[$key][1];
           if(array_key_exists($key,$staff[0]->getPourcentageCA())){
           $tvaCA = $staff[0]->getPourcentageCA()[$key][0];}
           if(array_key_exists($key,$staff[0]->getTypecommission())){
           $typeCommision = $staff[0]->getTypecommission()[$key][0];}
           if(array_key_exists($key,$ListCommission)){
           $Tvaparproduit = $ListCommission[$key];}
           
           if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false" ) &&  ($conditions[$key][2]== "" || $conditions[$key][2] =="false" ) && ($conditions[$key][1]== "" || $conditions[$key][1]=="false") ){
             $tvapatronale = $staff[0]->getCharges()[$key][0];
             $chargesalariale = $staff[0]->getCharges()[$key][1];
           }
           if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "" || $conditions[$key][1] == "false" )){
             $tvapatronale = $staff[0]->getParametre()['tauxJEI'] ; 
           }
           if( ($conditions[$key][0]== "0" || $conditions[$key][0] =="false" ) && ($conditions[$key][2]== "" || $conditions[$key][2] == "false") && ($conditions[$key][1]== "1" || $conditions[$key][1] == "true" )){
             $tvapatronale = $staff[0]->getParametre()['tauxmoyen'] ;
             $chargesalariale  = 0 ; 
           }
           if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true") && ($conditions[$key][0]== "" || $conditions[$key][0] =="false") ){
             $chargesalariale  = 0 ;
             $tvapatronale =  0 ;
           }
           if( ($conditions[$key][2]== "1" || $conditions[$key][2] =="true" )  && ($conditions[$key][0]== "1" || $conditions[$key][0]  =="true") && ($conditions[$key][1]== "" || $conditions[$key][1]  =="false") ){
             $chargesalariale  = 0 ;
             $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ;  
           }
           if( ($conditions[$key][0]== "" || $conditions[$key][0] =="false") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
             $chargesalariale  = 0 ;
             $tvapatronale = 0 ;  
           }
           if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "" || $conditions[$key][2]=="false") ){
             $chargesalariale  = 0 ;
             $tvapatronale = $staff[0]->getParametre()['tauxJEI']  ;  
           }
           if( ($conditions[$key][0]== "1" || $conditions[$key][0] =="true") && ( $conditions[$key][1]== "1" || $conditions[$key][1] =="true" ) && ($conditions[$key][2]== "1" || $conditions[$key][2]=="true") ){
             $chargesalariale  = 0 ;
             $tvapatronale =  $staff[0]->getParametre()['tauxJEI'] ; 
           }
        
          // dump($tvapatronale);die();
          foreach($value as $position=>$chiffre){
            if($typeCommision == '1'){//si le typecommision de tye Chiffre d'affaire
              if($chiffre >0){
           $commisionRec[$i][$position]+= ($SumfinalCAperMouth[$i][$position] * $tvaCA)/100 + ((($SumfinalCAperMouth[$i][$position] * $tvaCA)/100)* $tvapatronale) /100 ;}
           else{
             $commisionRec[$i][$position]+= 0 ;
           }
          } 
          if($typeCommision == '2'){//si le typecommission de type Produit
           if($chiffre >0){
            foreach($Tvaparproduit as $nomproduit=>$valueproduit){
 $commisionRec[$i][$position]+= ($finalCA[$nomproduit][$i][$position] * $valueproduit) /100 +   ((($finalCA[$nomproduit][$i][$position] * $valueproduit) /100)* $tvapatronale) /100;
          }}
          else{ $commisionRec[$i][$position]+= 0 ;}
         }
           $totalsalairbrutRec[$i][$position] += $chiffre * $salairebrut[$key][$i];
           $couttotalpersonemRec[$i][$position]+= ($chiffre * $salairebrut[$key][$i] *$tvapatronale ) /100 + $chiffre * $salairebrut[$key][$i];
           $NetapayerRec[$i][$position] += $chiffre * $salairebrut[$key][$i] - ($chiffre * $salairebrut[$key][$i] * $chargesalariale) /100 ;
           $DecchargesalarialesRec[$i][$position+ 1] +=  ($chiffre * $salairebrut[$key][$i] * $chargesalariale) /100 ;
           //dump($staff[0]->getCharges()[$key][0]);die();
         }}}
         for($i =0 ; $i<$years;$i++){
          for($x=0;$x<12;$x++){//calculer le decaissement mensuel
            
            $DecchargeemployeurhorsgerantRec[$i][$x+ 1] = $couttotalpersonemRec[$i][$x] - $totalsalairbrutRec[$i][$x];
            
          }}
          for($i =0 ; $i<$years;$i++){
          for($x=0;$x<13;$x++){
            $DecchargesRec[$i][$x] = $DecchargesalarialesRec[$i][$x] + $DecchargeemployeurhorsgerantRec[$i][$x];
          }}
         //decaissement total
          for($i =0 ; $i<$years;$i++){
          for($x=0;$x<13;$x++){
            if($x<12){
            $TotalDecaissementRec[$i][$x] += $NetapayerRec[$i][$x] + $DecchargesRec[$i][$x] + $commisionRec[$i][$x];}
            else{
              
              $TotalDecaissementRec[$i+1][0] += $DecchargesRec[$i][$x];
            }
          }}
  
          //reformuler les liste de decaissemnt 
          for($i =0 ; $i<$years;$i++){
            for($x=0;$x<13;$x++){
              if($x<12){
                $lastDecchargeemployeurhorsgerantRec[$i][$x] += $DecchargeemployeurhorsgerantRec[$i][$x];
                $lastDecchargesalarialesRec[$i][$x] += $DecchargesalarialesRec[$i][$x];
                $lastDecchargesRec[$i][$x] += $DecchargesRec[$i][$x];}
                else{
                 
                  $lastDecchargeemployeurhorsgerantRec[$i+1][0] += $DecchargeemployeurhorsgerantRec[$i][$x] ;
                  $lastDecchargesalarialesRec[$i+1][0] += $DecchargesalarialesRec[$i][$x];
                  $lastDecchargesRec[$i+1][$i] += $DecchargesRec[$i][$x];
                }
            }}
       //-----------------------------------FinRecherche----------------------------------//
       //---------------------------------Calcul total decaissement-----------------------//
       for($i =0 ; $i<$years;$i++){
        for($x=0;$x<12;$x++){
          $TOTAL[$i][$x] = $TotalDecaissementAdm[$i][$x] + $TotalDecaissementPro[$i][$x] + $TotalDecaissementCom[$i][$x] + $TotalDecaissementRec[$i][$x];
        }}
       //------------------------------------FinCalcul------------------------------------//
       // dump($lastDecchargeemployeurhorsgerantAdm,$lastDecchargesalarialesAdm,$lastDecchargesAdm);die();
     // ------------------------Fin de calcul --------------------------------------//
        $form = $this->createForm(CollectionFormType::class,$staffdetail[$id]);
        }
       else{
        $form = $this->createForm(CollectionFormType::class);
       }
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
     
            $entityManager->flush();
            return $this->redirectToRoute('staffdetail',['id'=>$id]);
           }
        return $this->render('staff/detail.html.twig',[
            'business'=> $businessSession , 'keyadmin' => $keyadmin,'keypro'=>$keypro ,'keycom'=>$keycom,'keyrec'=>$keyrec,'form'=>$form->createView()
        ,'ETP' => $this->ETP, 'ETPpro' => $this->ETPpro,'ETPcom' => $this->ETPcom   , 'ETPrec' => $this->ETPrec,'id'=> $id,'salairebrut' => $salairebrut,
        'totalsalairbrutAdm' => $totalsalairbrutAdm,'totalsalairbrutPro' => $totalsalairbrutPro,'totalsalairbrutCom' => $totalsalairbrutCom,'totalsalairbrutRec' => $totalsalairbrutRec,
         'couttotalpersonemAdm' => $couttotalpersonemAdm,'couttotalpersonemPro' => $couttotalpersonemPro,'couttotalpersonemCom' => $couttotalpersonemCom,'couttotalpersonemRec' => $couttotalpersonemRec,
         'NetapayerAdm'=> $NetapayerAdm,'NetapayerPro'=> $NetapayerPro,'NetapayerCom'=> $NetapayerCom,'NetapayerRec'=> $NetapayerRec,
         'TotalDecaissementAdm' =>$TotalDecaissementAdm,'TotalDecaissementPro' =>$TotalDecaissementPro,'TotalDecaissementCom' =>$TotalDecaissementCom,'TotalDecaissementRec' =>$TotalDecaissementRec,
         'horsgerant' =>$lastDecchargeemployeurhorsgerantAdm,'horsgerantPro' =>$lastDecchargeemployeurhorsgerantPro,'horsgerantCom' =>$lastDecchargeemployeurhorsgerantCom,'horsgerantRec' =>$lastDecchargeemployeurhorsgerantRec,
         'chargesalariale'=> $lastDecchargesalarialesAdm,'chargesalarialePro'=> $lastDecchargesalarialesPro,'chargesalarialeCom'=> $lastDecchargesalarialesCom,'chargesalarialeRec'=> $lastDecchargesalarialesRec,
         'charges'=> $lastDecchargesAdm,'chargesPro'=> $lastDecchargesPro,'chargesCom'=> $lastDecchargesCom,'chargesRec'=> $lastDecchargesRec, 
         'commissionAdm' => $commisionAdm ,'commissionPro' => $commisionPro , 'commissionCom' => $commisionCom , 'commissionRec' => $commisionRec
         ,'TOTAL' => $TOTAL,
            ]);
    }
    /**
     * @Route("/position", name="staffposition")
     */
    public function createposition(Request $request){
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
        $staffdetail = $entityManager->getRepository(Staffdetail::class)->findBy(["staff" => $staff]);
        $empty = false;
        $rangeofdetail = $businessSession->getRangeofdetail();
        $years = $businessSession->getNumberofyears();
        $rangeofglobal = $years - $rangeofdetail;
        $Administration = [];
      $Production = [];
      $Commercial = [];
      $Recherche = [];
      $salairebrut = [];
      $condition = [];
      $charges = [] ; 
      $parametre['forfait']  = "0" ;
      $parametre['tauxmoyen'] = "35";
      $parametre['tauxJEI'] = "18";
      $parametre['decaissement'] = "3";
      if($staff==null && $staffdetail == null){
        $empty= true ;
      $staff = new Staff();
      $staffdetail = new Staffdetail();
      }
      else{
        
        $Administration = $staff[0]->getAdministration() ;  
        $Production = $staff[0]->getProduction() ;
        $Commercial = $staff[0]->getSales() ;
        $Recherche = $staff[0]->getRecherche() ;
        $salairebrut = $staff[0]->getSalairebrut();
        $condition = $staff[0]->getConditions();
        $charges = $staff[0]->getCharges();
        $typecommission = $staff[0]->getTypecommission();
        $ca = $staff[0]->getPourcentageCA();
        $commissionproduit = $staff[0]->getCommissionproduit();
        for($i=0; $i<$years;$i++){
          $Administrationdetail[$i] = $staffdetail[$i]->getAdministration();
          $Productiondetail[$i] =  $staffdetail[$i]->getProduction();
          $Commercialdetail[$i] =  $staffdetail[$i]->getSales();
          $Recherchedetail[$i] =  $staffdetail[$i]->getRecherche();
        }}
       
        $form = $this->createForm(StaffCreateFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          
            if($form->getData()['Name'] != '' && array_key_exists($form->getData()['Name'],$salairebrut) == false){
                if($form->getData()['Department']==0 ){
                    for($i =0 ;$i<$rangeofglobal;$i++){
                        $Administration[$form->getData()['Name']][$i] = "0.00";
                      }
                      for($i =0 ;$i<$years;$i++){
                        $salairebrut[$form->getData()['Name']][$i] = "0.00";
                      }
                      $condition[$form->getData()['Name']][0]= ''.$form->getData()['JEI'];
                      $condition[$form->getData()['Name']][1]= ''.$form->getData()['TNS'];
                      $condition[$form->getData()['Name']][2]= ''.$form->getData()['Gratification'];
                      $charges[$form->getData()['Name']][0]= ''.$form->getData()['ChargePatronale'];
                      $charges[$form->getData()['Name']][1]= ''.$form->getData()['ChargeSalariales'];
                      $typecommission[$form->getData()['Name']] = ["0"];
                      $ca[$form->getData()['Name']] = [""];
                      $commissionproduit[$form->getData()['Name']]  = [""];
                      for($i=0; $i<$years;$i++){
                        for($x=0 ; $x<12;$x++){
                         $Administrationdetail[$i][$form->getData()['Name']][$x] = "0.00";}}
                         if($empty == true ){
                            $staff->setAdministration($Administration);
                            $staff->setSalairebrut($salairebrut);
                            $staff->setConditions($condition);
                            $staff->setCharges($charges);
                            $staff->setParametre($parametre);
                            $staff->setBusinessplan($businessSession);
                            $staff->setTypecommission($typecommission);
                            $staff->setPourcentageCA($ca);
                            $staff->setCommissionproduit($commissionproduit);
                            $entityManager->merge($staff);
                            $entityManager->flush();
                            //dump($investments);die();
                            $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
                            for($i=0; $i<$years;$i++){  
                              //dump($investments);die();
                              $staffdetail->setYear($i);
                              $staffdetail->setAdministration($Administrationdetail[$i]);
                              $staffdetail->setStaff($staff[0]);
                              $entityManager->merge($staffdetail);
                              
                              }
                           
                           }
                           else{
                          
                           
                           $staff[0]->setAdministration($Administration);
                           $staff[0]->setSalairebrut($salairebrut);
                           $staff[0]->setConditions($condition);
                           $staff[0]->setCharges($charges);
                           $staff[0]->setTypecommission($typecommission);
                           $staff[0]->setPourcentageCA($ca);
                           $staff[0]->setCommissionproduit($commissionproduit);
                           for($i=0; $i<$years;$i++){
                           $staffdetail[$i]->setAdministration($Administrationdetail[$i]);}
                               
                           //$investments[0]->setBusinessplan($businessSession);
                           }
                    }
          if($form->getData()['Department']==1 ){
            for($i =0 ;$i<$rangeofglobal;$i++){
             $Production[$form->getData()['Name']][$i] = "0.00";}
             for($i =0 ;$i<$years;$i++){
              $salairebrut[$form->getData()['Name']][$i] = "0.00";}
              $condition[$form->getData()['Name']][0]= ''.$form->getData()['JEI'];
              $condition[$form->getData()['Name']][1]= ''.$form->getData()['TNS'];
              $condition[$form->getData()['Name']][2]= ''.$form->getData()['Gratification'];
              $charges[$form->getData()['Name']][0]= ''.$form->getData()['ChargePatronale'];
              $charges[$form->getData()['Name']][1]= ''.$form->getData()['ChargeSalariales'];
              $typecommission[$form->getData()['Name']] = ["0"];
              $ca[$form->getData()['Name']] = [""];
               $commissionproduit[$form->getData()['Name']]  = [""];
              for($i=0; $i<$years;$i++){
              for($x=0 ; $x<12;$x++){
              $Productiondetail[$i][$form->getData()['Name']][$x] = "0.00";}}
              if($empty == true ){
              $staff->setProduction($Production);
              $staff->setSalairebrut($salairebrut);
              $staff->setConditions($condition);
              $staff->setCharges($charges);
              $staff->setParametre($parametre);
              $staff->setBusinessplan($businessSession);
              $staff->setTypecommission($typecommission);
              $staff->setPourcentageCA($ca);
              $staff->setCommissionproduit($commissionproduit);
              $entityManager->merge($staff);
              $entityManager->flush();
              //dump($investments);die();
              $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
              for($i=0; $i<$years;$i++){  
              //dump($investments);die();
              $staffdetail->setYear($i);
              $staffdetail->setProduction($Productiondetail[$i]);
              $staffdetail->setStaff($staff[0]);
              $entityManager->merge($staffdetail);}}
              else{
              $staff[0]->setProduction($Production);
              $staff[0]->setSalairebrut($salairebrut);
              $staff[0]->setConditions($condition);
              $staff[0]->setCharges($charges);
              $staff[0]->setTypecommission($typecommission);
              $staff[0]->setPourcentageCA($ca);
              $staff[0]->setCommissionproduit($commissionproduit);
              for($i=0; $i<$years;$i++){
              $staffdetail[$i]->setProduction($Productiondetail[$i]);}
              }
              }
              if($form->getData()['Department']==2 ){
                for($i =0 ;$i<$rangeofglobal;$i++){
                 $Commercial[$form->getData()['Name']][$i] = "0.00";}
                 for($i =0 ;$i<$years;$i++){
                  $salairebrut[$form->getData()['Name']][$i] = "0.00";}
                  $condition[$form->getData()['Name']][0]= ''.$form->getData()['JEI'];
                  $condition[$form->getData()['Name']][1]= ''.$form->getData()['TNS'];
                  $condition[$form->getData()['Name']][2]= ''.$form->getData()['Gratification'];
                  $charges[$form->getData()['Name']][0]= ''.$form->getData()['ChargePatronale'];
                  $charges[$form->getData()['Name']][1]= ''.$form->getData()['ChargeSalariales'];
                  $typecommission[$form->getData()['Name']] = ["0"];
                  $ca[$form->getData()['Name']] = [""];
                   $commissionproduit[$form->getData()['Name']]  = [""];
                  for($i=0; $i<$years;$i++){
                  for($x=0 ; $x<12;$x++){
                  $Commercialdetail[$i][$form->getData()['Name']][$x] = "0.00";}}
                  if($empty == true ){
                  $staff->setSales($Commercial);
                  $staff->setSalairebrut($salairebrut);
                  $staff->setConditions($condition);
                  $staff->setCharges($charges);
                  $staff->setParametre($parametre);
                  $staff->setBusinessplan($businessSession);
                  $staff->setTypecommission($typecommission);
                  $staff->setPourcentageCA($ca);
                  $staff->setCommissionproduit($commissionproduit);
                  $entityManager->merge($staff);
                  $entityManager->flush();
                  //dump($investments);die();
                  $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
                  for($i=0; $i<$years;$i++){  
                  //dump($investments);die();
                  $staffdetail->setYear($i);
                  $staffdetail->setSales($Commercialdetail[$i]);
                  $staffdetail->setStaff($staff[0]);
                  $entityManager->merge($staffdetail);}}
                  else{
                  $staff[0]->setSales($Commercial);
                  $staff[0]->setSalairebrut($salairebrut);
                  $staff[0]->setConditions($condition);
                  $staff[0]->setCharges($charges);
                  $staff[0]->setTypecommission($typecommission);
                  $staff[0]->setPourcentageCA($ca);
                  $staff[0]->setCommissionproduit($commissionproduit);
                  for($i=0; $i<$years;$i++){
                  $staffdetail[$i]->setSales($Commercialdetail[$i]);}
                  }
                  } 
                  if($form->getData()['Department']==3 ){
                    for($i =0 ;$i<$rangeofglobal;$i++){
                     $Recherche[$form->getData()['Name']][$i] = "0.00";}
                     for($i =0 ;$i<$years;$i++){
                      $salairebrut[$form->getData()['Name']][$i] = "0.00";}
                      $condition[$form->getData()['Name']][0]= ''.$form->getData()['JEI'];
                      $condition[$form->getData()['Name']][1]= ''.$form->getData()['TNS'];
                      $condition[$form->getData()['Name']][2]= ''.$form->getData()['Gratification'];
                      $charges[$form->getData()['Name']][0]= ''.$form->getData()['ChargePatronale'];
                      $charges[$form->getData()['Name']][1]= ''.$form->getData()['ChargeSalariales'];
                      $typecommission[$form->getData()['Name']] = ["0"];
                      $ca[$form->getData()['Name']] = [""];
                      $commissionproduit[$form->getData()['Name']]  = [""];
                      for($i=0; $i<$years;$i++){
                      for($x=0 ; $x<12;$x++){
                      $Recherchedetail[$i][$form->getData()['Name']][$x] = "0.00";}}
                      if($empty == true ){
                      $staff->setRecherche($Recherche);
                      $staff->setSalairebrut($salairebrut);
                      $staff->setConditions($condition);
                      $staff->setCharges($charges);
                      $staff->setParametre($parametre);
                      $staff->setBusinessplan($businessSession);
                      $staff->setTypecommission($typecommission);
                      $staff->setPourcentageCA($ca);
                      $staff->setCommissionproduit($commissionproduit);
                      $entityManager->merge($staff);
                      $entityManager->flush();
                      //dump($investments);die();
                      $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
                      for($i=0; $i<$years;$i++){  
                      //dump($investments);die();
                      $staffdetail->setYear($i);
                      $staffdetail->setRecherche($Recherchedetail[$i]);
                      $staffdetail->setStaff($staff[0]);
                      $entityManager->merge($staffdetail);}}
                      else{
                      $staff[0]->setRecherche($Recherche);
                      $staff[0]->setSalairebrut($salairebrut);
                      $staff[0]->setConditions($condition);
                      $staff[0]->setCharges($charges);
                      $staff[0]->setTypecommission($typecommission);
                      $staff[0]->setPourcentageCA($ca);
                      $staff[0]->setCommissionproduit($commissionproduit);
                      for($i=0; $i<$years;$i++){
                      $staffdetail[$i]->setRecherche($Recherchedetail[$i]);}
                      }
                      }  
            }
            $entityManager->flush();
            return $this->redirectToRoute('staff');
        }
       
        return $this->render('staff/position.html.twig',['form'=>$form->createView()]);
    }
    /**
     * @Route("/edit/{name}", name="staffedit")
     */
    public function edit(Request $request,$name){
      $businessSession =$this->container->get('session')->get('business');
      $entityManager = $this->getDoctrine()->getManager();
      $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
      $staffdetail = $entityManager->getRepository(Staffdetail::class)->findBy(["staff" => $staff]);
      $products =$entityManager->getRepository(Product::class)->findByBusinessplan($businessSession);
      $rangeofdetail = $businessSession->getRangeofdetail();
      $Listproduct =[];
      $department ;
      $parametre = $staff[0]->getParametre();
      $TypeCommission[0]="0";
      $CA[0] = "";
      $years = $businessSession->getNumberofyears();
      $rangeofglobal = $years - $rangeofdetail;
      for($i = 0 ; $i<$years;$i++){
        $Adminlist[$i] =  $staffdetail[$i]->getAdministration();
        $Productionlist[$i] = $staffdetail[$i]->getProduction();
        $CommercialList[$i] =  $staffdetail[$i]->getSales();
        $RechercheList[$i] =  $staffdetail[$i]->getRecherche();}
      foreach($products as $key=>$value){
        array_push($Listproduct,$value->getName());
      }
    
        $status = '';
        if(array_key_exists($name, $Adminlist[0]) == true){
          $status = 'Administration';
          $AdminGlobalList = $staff[0]->getAdministration() ;
          $department = "0";
         }
         if(array_key_exists($name, $Productionlist[0]) == true){
          $status = 'Production';
          $ProductionGlobalList = $staff[0]->getProduction() ;
          $department = "1";
         }
         if(array_key_exists($name, $CommercialList[0]) == true){
          $status = 'Commercial';
          $CommercialGlobalList =  $staff[0]->getSales() ;
          $department = "2";
         }
         if(array_key_exists($name, $RechercheList[0]) == true){
          $status = 'Recherche';
          $RechercheGlobalList =  $staff[0]->getRecherche() ;
          $department = "3";
         }
      $JEIstatus = True ;
      $TNSstatus = True ;
      $GRAstatus = True ;
      $salairebrut = $staff[0]->getSalairebrut();
      $condition = $staff[0]->getConditions()[$name];
      $charges =  $staff[0]->getCharges()[$name];
      $Listcharges = $staff[0]->getCharges();
      $ListConditions = $staff[0]->getConditions();
      if(array_key_exists($name, $staff[0]->getTypecommission()) == true){
      $TypeCommission  = $staff[0]->getTypecommission()[$name];}
      if(array_key_exists($name, $staff[0]->getPourcentageCA()) == true){ 
        $CA = $staff[0]->getPourcentageCA()[$name];}
      if(array_key_exists($name, $staff[0]->getCommissionproduit()) == true){ 
          $commisionproductone = $staff[0]->getCommissionproduit()[$name];}
      
      
      $TypeCommisionall = $staff[0]->getTypecommission() ;
      $CAall = $staff[0]->getPourcentageCA();
      $commisionproduct = $staff[0]->getCommissionproduit();
      $JEI = $condition[0];
      $TNS = $condition[1];
      $Grafitation = $condition[2];
      if($JEI== ""){
        $JEIstatus = false;
      }
      if($TNS==""){
        $TNSstatus = false; 
      }
      if($Grafitation == ""){
        $GRAstatus = false ;
      }
      //dump($TypeCommission);die();
      $form = $this->createForm(StaffEditFormType::class,['Department' => $department ,'JEI' => $JEIstatus 
      ,'TNS'=> $TNSstatus , 'Gratification'=>$GRAstatus ,'Typecommision' =>$TypeCommission[0] ,
      ]);
    
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
       
            ${"admin" . $name}= [];
            if($status == 'Administration' && $AdminGlobalList != []){
             ${"admin" . $name}=$AdminGlobalList[$name]; // conserver les valeurs  avant de supprimer
             ${"salaire".$name}= $salairebrut[$name];
             ${"charge".$name}= $charges;
             ${"condition".$name}= $condition;
             ${"typecommision".$name} = $TypeCommission;
             ${"chiffre".$name} = $CA;
             ${"produit".$name} = $commisionproductone;
             for($i = 0 ; $i<$years;$i++){    
              ${"d" . $i} = $Adminlist[$i][$name];
              unset($Adminlist[$i][$name]);
              $staffdetail[$i]->setAdministration($Adminlist[$i]);
            }
            
             unset($AdminGlobalList[$name]);
             unset($salairebrut[$name]);
             unset($Listcharges[$name]);
             unset($ListConditions[$name]);
             unset($commisionproduct[$name]);
             unset($CAall[$name]);
             unset($TypeCommisionall[$name]);
              $staff[0]->setAdministration($AdminGlobalList);
              $staff[0]->setSalairebrut($salairebrut);
              $staff[0]->setCharges($Listcharges);
              $staff[0]->setConditions($ListConditions);
              $staff[0]->setTypecommission($TypeCommisionall);
              $staff[0]->setPourcentageCA($CAall);
              $staff[0]->setCommissionproduit($commisionproduct);
            }
           else if($status == 'Administration' && $AdminGlobalList == []){
          
            ${"salaire".$name}= $salairebrut[$name];
            ${"charge".$name}= $charges;
            ${"condition".$name}= $condition;
            ${"typecommision".$name} = $TypeCommission;
            ${"chiffre".$name} = $CA;
            ${"produit".$name} = $commisionproductone;
            for($i = 0 ; $i<$years;$i++){    
             ${"d" . $i} = $Adminlist[$i][$name];
             unset($Adminlist[$i][$name]);
             $staffdetail[$i]->setAdministration($Adminlist[$i]);
           }
            unset($salairebrut[$name]);
            unset($Listcharges[$name]);
            unset($ListConditions[$name]);
            unset($commisionproduct[$name]);
            unset($CAall[$name]);
            unset($TypeCommisionall[$name]);
             $staff[0]->setSalairebrut($salairebrut);
             $staff[0]->setCharges($Listcharges);
             $staff[0]->setConditions($ListConditions);
             $staff[0]->setTypecommission($TypeCommisionall);
             $staff[0]->setPourcentageCA($CAall);
             $staff[0]->setCommissionproduit($commisionproduct);
           }
            if($status == 'Production' && $ProductionGlobalList != []){
              ${"admin" . $name}=$ProductionGlobalList[$name]; // conserver les valeurs  avant de supprimer
              ${"salaire".$name}= $salairebrut[$name];
              ${"charge".$name}= $charges;
              ${"condition".$name}= $condition;
              ${"typecommision".$name} = $TypeCommission;
              ${"chiffre".$name} = $CA;
              ${"produit".$name} = $commisionproductone;
              for($i = 0 ; $i<$years;$i++){    
               ${"d" . $i} = $Productionlist[$i][$name];
               unset($Productionlist[$i][$name]);
               $staffdetail[$i]->setProduction($Productionlist[$i]);
             }
              unset($ProductionGlobalList[$name]);
              unset($salairebrut[$name]);
              unset($Listcharges[$name]);
              unset($ListConditions[$name]);
              unset($commisionproduct[$name]);
              unset($CAall[$name]);
              unset($TypeCommisionall[$name]);
               $staff[0]->setProduction($ProductionGlobalList);
               $staff[0]->setSalairebrut($salairebrut);
               $staff[0]->setCharges($Listcharges);
               $staff[0]->setConditions($ListConditions);
               $staff[0]->setTypecommission($TypeCommisionall);
               $staff[0]->setPourcentageCA($CAall);
               $staff[0]->setCommissionproduit($commisionproduct);
             }
             else if($status == 'Production' && $ProductionGlobalList == []){
              ${"salaire".$name}= $salairebrut[$name];
              ${"charge".$name}= $charges;
              ${"condition".$name}= $condition;
              ${"typecommision".$name} = $TypeCommission;
              ${"chiffre".$name} = $CA;
              ${"produit".$name} = $commisionproductone;
              for($i = 0 ; $i<$years;$i++){    
               ${"d" . $i} = $Productionlist[$i][$name];
               unset($Productionlist[$i][$name]);
               $staffdetail[$i]->setProduction($Productionlist[$i]);
             }
              
              unset($salairebrut[$name]);
              unset($Listcharges[$name]);
              unset($ListConditions[$name]);
              unset($commisionproduct[$name]);
              unset($CAall[$name]);
              unset($TypeCommisionall[$name]);
              
               $staff[0]->setSalairebrut($salairebrut);
               $staff[0]->setCharges($Listcharges);
               $staff[0]->setConditions($ListConditions);
               $staff[0]->setTypecommission($TypeCommisionall);
               $staff[0]->setPourcentageCA($CAall);
               $staff[0]->setCommissionproduit($commisionproduct);
             }
             if($status == 'Commercial' && $CommercialGlobalList != []){
              ${"admin" . $name}=$CommercialGlobalList[$name]; // conserver les valeurs  avant de supprimer
              ${"salaire".$name}= $salairebrut[$name];
              ${"charge".$name}= $charges;
              ${"condition".$name}= $condition;
              ${"typecommision".$name} = $TypeCommission;
              ${"chiffre".$name} = $CA;
              ${"produit".$name} = $commisionproductone;
              for($i = 0 ; $i<$years;$i++){    
               ${"d" . $i} = $CommercialList[$i][$name];
               unset($CommercialList[$i][$name]);
               $staffdetail[$i]->setSales($CommercialList[$i]);
             }
              unset($CommercialGlobalList[$name]);
              unset($salairebrut[$name]);
              unset($Listcharges[$name]);
              unset($ListConditions[$name]);
              unset($commisionproduct[$name]);
              unset($CAall[$name]);
              unset($TypeCommisionall[$name]);
               $staff[0]->setSales($CommercialGlobalList);
               $staff[0]->setSalairebrut($salairebrut);
               $staff[0]->setCharges($Listcharges);
               $staff[0]->setConditions($ListConditions);
               $staff[0]->setTypecommission($TypeCommisionall);
               $staff[0]->setPourcentageCA($CAall);
               $staff[0]->setCommissionproduit($commisionproduct);
             }
             else if($status == 'Commercial' && $CommercialGlobalList == []){
              ${"salaire".$name}= $salairebrut[$name];
              ${"charge".$name}= $charges;
              ${"condition".$name}= $condition;
              ${"typecommision".$name} = $TypeCommission;
              ${"chiffre".$name} = $CA;
              ${"produit".$name} = $commisionproductone;
              for($i = 0 ; $i<$years;$i++){    
               ${"d" . $i} = $CommercialList[$i][$name];
               unset($CommercialList[$i][$name]);
               $staffdetail[$i]->setSales($CommercialList[$i]);
             }
              unset($salairebrut[$name]);
              unset($Listcharges[$name]);
              unset($ListConditions[$name]);
              unset($commisionproduct[$name]);
              unset($CAall[$name]);
              unset($TypeCommisionall[$name]);
               $staff[0]->setSalairebrut($salairebrut);
               $staff[0]->setCharges($Listcharges);
               $staff[0]->setConditions($ListConditions);
               $staff[0]->setTypecommission($TypeCommisionall);
               $staff[0]->setPourcentageCA($CAall);
               $staff[0]->setCommissionproduit($commisionproduct);

             }
             if($status == 'Recherche' && $RechercheGlobalList != []){
              ${"admin" . $name}=$RechercheGlobalList[$name]; // conserver les valeurs  avant de supprimer
              ${"salaire".$name}= $salairebrut[$name];
              ${"charge".$name}= $charges;
              ${"condition".$name}= $condition;
              ${"typecommision".$name} = $TypeCommission;
              ${"chiffre".$name} = $CA;
              ${"produit".$name} = $commisionproductone;
              for($i = 0 ; $i<$years;$i++){    
               ${"d" . $i} = $RechercheList[$i][$name];
               unset($RechercheList[$i][$name]);
               $staffdetail[$i]->setRecherche($RechercheList[$i]);
             }
              unset($RechercheGlobalList[$name]);
              unset($salairebrut[$name]);
              unset($Listcharges[$name]);
              unset($ListConditions[$name]);
              unset($commisionproduct[$name]);
              unset($CAall[$name]);
              unset($TypeCommisionall[$name]);
               $staff[0]->setRecherche($RechercheGlobalList);
               $staff[0]->setSalairebrut($salairebrut);
               $staff[0]->setCharges($Listcharges);
               $staff[0]->setConditions($ListConditions);
               $staff[0]->setTypecommission($TypeCommisionall);
               $staff[0]->setPourcentageCA($CAall);
               $staff[0]->setCommissionproduit($commisionproduct);
             }
             else if($status == 'Recherche' && $RechercheGlobalList == []){
              ${"salaire".$name}= $salairebrut[$name];
              ${"charge".$name}= $charges;
              ${"condition".$name}= $condition;
              ${"typecommision".$name} = $TypeCommission;
              ${"chiffre".$name} = $CA;
              ${"produit".$name} = $commisionproductone;
              for($i = 0 ; $i<$years;$i++){    
               ${"d" . $i} = $RechercheList[$i][$name];
               unset($RechercheList[$i][$name]);
               $staffdetail[$i]->setRecherche($RechercheList[$i]);
             }
              
              unset($salairebrut[$name]);
              unset($Listcharges[$name]);
              unset($ListConditions[$name]);
              unset($commisionproduct[$name]);
              unset($CAall[$name]);
              unset($TypeCommisionall[$name]);
               $staff[0]->setRecherche($RechercheGlobalList);
               $staff[0]->setSalairebrut($salairebrut);
               $staff[0]->setCharges($Listcharges);
               $staff[0]->setConditions($ListConditions);
               $staff[0]->setTypecommission($TypeCommisionall);
               $staff[0]->setPourcentageCA($CAall);
               
             }
            if($form->getData()['Name'] != '' && array_key_exists($form->getData()['Name'],$salairebrut) == false){
              if($form->getData()['Department']==0 ){
                $commisionList =  explode(",",$form->getData()['product']);
                
                $Listcharges[$form->getData()['Name']] = [''.$form->getData()['ChargePatronale'],''.$form->getData()['ChargeSalariales']];
                $salairebrut[$form->getData()['Name']]= ${"salaire".$name} ; 
                $ListConditions[$form->getData()['Name']]= [$form->getData()['JEI'],$form->getData()['TNS'],$form->getData()['Gratification']] ; 
                $TypeCommisionall[$form->getData()['Name']] = [''.$form->getData()['Typecommision']];
                $CAall[$form->getData()['Name']] = [''.$form->getData()['CA']];  
                $commisionproduct[$form->getData()['Name']] = $commisionList;
                
                $staff[0]->setSalairebrut($salairebrut);
                $staff[0]->setCharges($Listcharges);
                $staff[0]->setConditions($ListConditions);
                $staff[0]->setTypecommission($TypeCommisionall);
                $staff[0]->setPourcentageCA($CAall);
                $staff[0]->setCommissionproduit($commisionproduct);
                for($i = 0 ; $i<$years;$i++){
                  $Adminlist[$i][$form->getData()['Name']] = ${"d" . $i};
                  $staffdetail[$i]->setAdministration($Adminlist[$i]);
                 } 
                 if($rangeofdetail < $rangeofglobal){ 
                if($staff[0]->getAdministration()!=[]){
                  $AdminGlobalList = $staff[0]->getAdministration() ;
                }
                $AdminGlobalList[$form->getData()['Name']] = ${"admin" . $name};
                $staff[0]->setAdministration($AdminGlobalList);}
              }
              if($form->getData()['Department']==1 ){
                
                $commisionList =  explode(",",$form->getData()['product']);
                
                $Listcharges[$form->getData()['Name']] = [''.$form->getData()['ChargePatronale'],''.$form->getData()['ChargeSalariales']];
                $salairebrut[$form->getData()['Name']]= ${"salaire".$name} ; 
                $ListConditions[$form->getData()['Name']]= [$form->getData()['JEI'],$form->getData()['TNS'],$form->getData()['Gratification']] ; 
                $TypeCommisionall[$form->getData()['Name']] = [''.$form->getData()['Typecommision']];
                $CAall[$form->getData()['Name']] = [''.$form->getData()['CA']];  
                $commisionproduct[$form->getData()['Name']] = $commisionList;
                
                $staff[0]->setSalairebrut($salairebrut);
                $staff[0]->setCharges($Listcharges);
                $staff[0]->setConditions($ListConditions);
                $staff[0]->setTypecommission($TypeCommisionall);
                $staff[0]->setPourcentageCA($CAall);
                $staff[0]->setCommissionproduit($commisionproduct);
                for($i = 0 ; $i<$years;$i++){
                  $Productionlist[$i][$form->getData()['Name']] = ${"d" . $i};
                  $staffdetail[$i]->setProduction($Productionlist[$i]);
                 } 
                 if($rangeofdetail < $rangeofglobal){ 
                 if($staff[0]->getProduction()!=[]){
                  $ProductionGlobalList = $staff[0]->getProduction() ;
                }
                $ProductionGlobalList[$form->getData()['Name']] = ${"admin" . $name};
                
                $staff[0]->setProduction($ProductionGlobalList);}
              }
              if($form->getData()['Department']==2 ){
                $commisionList =  explode(",",$form->getData()['product']);
                
                $Listcharges[$form->getData()['Name']] = [''.$form->getData()['ChargePatronale'],''.$form->getData()['ChargeSalariales']];
                $salairebrut[$form->getData()['Name']]= ${"salaire".$name} ; 
                $ListConditions[$form->getData()['Name']]= [$form->getData()['JEI'],$form->getData()['TNS'],$form->getData()['Gratification']] ; 
                $TypeCommisionall[$form->getData()['Name']] = [''.$form->getData()['Typecommision']];
                $CAall[$form->getData()['Name']] = [''.$form->getData()['CA']];  
                $commisionproduct[$form->getData()['Name']] = $commisionList;
                
                $staff[0]->setSalairebrut($salairebrut);
                $staff[0]->setCharges($Listcharges);
                $staff[0]->setConditions($ListConditions);
                $staff[0]->setTypecommission($TypeCommisionall);
                $staff[0]->setPourcentageCA($CAall);
                $staff[0]->setCommissionproduit($commisionproduct);
                for($i = 0 ; $i<$years;$i++){
                  $CommercialList[$i][$form->getData()['Name']] = ${"d" . $i};
                  $staffdetail[$i]->setSales($CommercialList[$i]);
                 }
                 if($rangeofdetail < $rangeofglobal){ 
                if($staff[0]->getSales()!=[]){
                  $CommercialGlobalList = $staff[0]->getSales() ;
                }
                $CommercialGlobalList[$form->getData()['Name']] = ${"admin" . $name};
                $staff[0]->setSales($CommercialGlobalList);}
              }
              if($form->getData()['Department']==3 ){
                $commisionList =  explode(",",$form->getData()['product']);
                
                $Listcharges[$form->getData()['Name']] = [''.$form->getData()['ChargePatronale'],''.$form->getData()['ChargeSalariales']];
                $salairebrut[$form->getData()['Name']]= ${"salaire".$name} ; 
                $ListConditions[$form->getData()['Name']]= [$form->getData()['JEI'],$form->getData()['TNS'],$form->getData()['Gratification']] ; 
                $TypeCommisionall[$form->getData()['Name']] = [''.$form->getData()['Typecommision']];
                $CAall[$form->getData()['Name']] = [''.$form->getData()['CA']];  
                $commisionproduct[$form->getData()['Name']] = $commisionList;
                
                $staff[0]->setSalairebrut($salairebrut);
                $staff[0]->setCharges($Listcharges);
                $staff[0]->setConditions($ListConditions);
                $staff[0]->setTypecommission($TypeCommisionall);
                $staff[0]->setPourcentageCA($CAall);
                $staff[0]->setCommissionproduit($commisionproduct);
                for($i = 0 ; $i<$years;$i++){
                  $RechercheList[$i][$form->getData()['Name']] = ${"d" . $i};
                  $staffdetail[$i]->setRecherche($RechercheList[$i]);
                 } 
                 if($rangeofdetail < $rangeofglobal){ 
                if($staff[0]->getRecherche()!=[]){
                  $RechercheGlobalList = $staff[0]->getRecherche() ;
                }
                $RechercheGlobalList[$form->getData()['Name']] = ${"admin" . $name};
                $staff[0]->setRecherche($RechercheGlobalList);}
              }
            }
              $entityManager->flush();
        return $this->redirectToRoute('staff');
      }

     //dump($CA);die();
      return $this->render('staff/edit.html.twig',[
      'name'=> $name , 'charges' => $charges,'Listproduct' => $Listproduct, 'CA' =>  $CA,
      'form'=>$form->createView() , 'product' => $Listproduct, 'JEI' => $parametre['tauxJEI'],
      ]);
    }
     /**
     * @Route("/delete/{name}", name="staffdelete")
     */
    public function delete($name){
      $entityManager = $this->getDoctrine()->getManager();
      $businessSession =$this->container->get('session')->get('business');
      $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
      $staffdetail = $entityManager->getRepository(Staffdetail::class)->findBy(["staff" => $staff]);
      $rangeofdetail = $businessSession->getRangeofdetail();
      $years = $businessSession->getNumberofyears();
      $rangeofglobal = $years - $rangeofdetail;
      $salairebrut = $staff[0]->getSalairebrut();
      $conditon = $staff[0]->getConditions();
      $charges = $staff[0]->getCharges();
      $TypeCommission  = $staff[0]->getTypecommission(); 
      $CA = $staff[0]->getPourcentageCA(); 
      $commissionproduit = $staff[0]->getCommissionproduit();
      for($i = 0 ; $i<$years;$i++){
        $Adminlist[$i] =  $staffdetail[$i]->getAdministration();
        $Productionlist[$i] = $staffdetail[$i]->getProduction();
        $CommercialList[$i] =  $staffdetail[$i]->getSales();
        $RechercheList[$i] =  $staffdetail[$i]->getRecherche();}
        $status = '';
        if(array_key_exists($name, $Adminlist[0]) == true){
          $status = 'Administration';
          $AdminGlobalList = $staff[0]->getAdministration() ;
          unset($AdminGlobalList[$name]);
          $staff[0]->setAdministration($AdminGlobalList);
          for($i = 0 ; $i<$years;$i++){        
            unset($Adminlist[$i][$name]);
            $staffdetail[$i]->setAdministration($Adminlist[$i]);
          }
         }
         if(array_key_exists($name, $Productionlist[0]) == true){
          $status = 'Production';
          $ProductionGlobalList = $staff[0]->getProduction() ;
          unset($ProductionGlobalList[$name]);
          $staff[0]->setProduction($ProductionGlobalList);
          for($i = 0 ; $i<$years;$i++){        
            unset($Productionlist[$i][$name]);
            $staffdetail[$i]->setProduction($Productionlist[$i]);
          }
         }
         if(array_key_exists($name, $CommercialList[0]) == true){
          $status = 'Commercial';
          $CommercialGlobalList =  $staff[0]->getSales() ;
          unset($CommercialGlobalList[$name]);
          $staff[0]->setSales($CommercialGlobalList);
          for($i = 0 ; $i<$years;$i++){        
            unset($CommercialList[$i][$name]);
            $staffdetail[$i]->setSales($CommercialList[$i]);
          }
         }
         if(array_key_exists($name, $RechercheList[0]) == true){
          $status = 'Recherche';
          $RechercheGlobalList =  $staff[0]->getRecherche() ;
          unset($RechercheGlobalList[$name]);
          $staff[0]->setRecherche($RechercheGlobalList);
          for($i = 0 ; $i<$years;$i++){        
            unset($RechercheList[$i][$name]);
            $staffdetail[$i]->setRecherche($RechercheList[$i]);
          }
         }
        unset($salairebrut[$name]);
        unset($conditon[$name]);
        unset($charges[$name]);
        unset($TypeCommission[$name]);
        unset($CA[$name]);
        unset($commissionproduit[$name]);
        $staff[0]->setSalairebrut($salairebrut);
        $staff[0]->setConditions($conditon);
        $staff[0]->setCharges($charges);
        $staff[0]->setTypecommission($TypeCommission);
        $staff[0]->setPourcentageCA($CA);
        $staff[0]->setCommissionproduit($commissionproduit);
        $entityManager->flush();
        return $this->redirectToRoute('staff');
    }
    /**
     * @Route("/manage_charges", name="gestioncharge")
     */
    public function gestion(Request $request){
      $businessSession =$this->container->get('session')->get('business');
      $entityManager = $this->getDoctrine()->getManager();
      $staff = $entityManager->getRepository(Staff::class)->findByBusinessplan($businessSession);
      $staffdetail = $entityManager->getRepository(Staffdetail::class)->findBy(["staff" => $staff]);
      $decaissement ='1';
      $forfait = '0' ;
      $tauxmoyen = '35';
      $JEI = '18';
      if($staff != []){
      $parametre = $staff[0]->getParametre();
      $forfait = $parametre['forfait'];
      $tauxmoyen = $parametre['tauxmoyen'];
      $JEI  = $parametre['tauxJEI'];
      $decaissement = $parametre['decaissement'];}
      $form = $this->createForm(ChargesFormType::class,[ 'Decaissement' => $decaissement ]); 
      $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          
          $parametre['forfait'] = $form->getData()['forfait'];
          $parametre['tauxmoyen'] = $form->getData()['Taux'];
          $parametre['tauxJEI'] = $form->getData()['JEI'];
          if($staff != [] ){
          $staff[0]->setParametre($parametre);}
          $entityManager->flush();
          return $this->redirectToRoute('staff');
        }
      return $this->render('staff/gestioncharge.html.twig',['business' => $businessSession ,
      'form' => $form->createView()  , 'forfait' => $forfait ,  'tauxmoyen' => $tauxmoyen , 'JEI'  => $JEI    
       ]);
    }
}
