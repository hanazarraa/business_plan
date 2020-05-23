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
    private $salairebrut = [];
    
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
        $ETPglobal = [];
        $coutannuel =[];
        if($staffdetail != null){
        $keyadmin = array_keys($staffdetail[0]->getAdministration());
        $ETPglobal = $staff[0]->getAdministration();
        $this->salairebrut = $staff[0]->getSalairebrut();
        $charges =   $staff[0]->getCharges();
        }
       
        $rangeofdetail = $businessSession->getRangeofdetail();
        $years = $businessSession->getNumberofyears();
        $rangeofglobal = $years - $rangeofdetail;
        for($i =0 ; $i<$years;$i++){
        $this->detail($request,$i,$productRepository,$SalesRepository);}
        foreach($this->ETP as $key=>$values){//Renverser la liste ETP avec saisie detailler
            foreach($values as $name=>$chiffre){
                $newETPdetailled[$name][$key] = $chiffre;
            }
           // $newETP[$values][$key] = $values;
        }
       foreach($newETPdetailled as $key=>$value){ // merger les liste ETP globale et detailler
           for($i = $rangeofdetail ; $i<$years;$i++){
              
            $value[$i]= $ETPglobal[$key][$i-$rangeofdetail];
            $newETPdetailled[$key][$i] = $value[$i];
           }    
       }
       
        if($staffdetail != null){
            foreach($this->salairebrut as $key=>$values){
            for($i=0;$i<$years;$i++){
            $coutannuel[$key][$i] =  round((($newETPdetailled[$key][$i]*12) * $values[$i]) +(($newETPdetailled[$key][$i]*12) * $values[$i]) * $charges[$key][0] /100 );
            }}

            
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
         'business'=> $businessSession,'form' => $form->createView(),'keyadmin' =>  $keyadmin,
          'rangeofdetail'=>$rangeofdetail , 'rangeofglobal'=>$rangeofglobal,'ETP'=>$this->ETP,
        'coutannuel' => $coutannuel
          ]);
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
        $salairebrut = [];
        $ListCommission =[];
        if($staffdetail != null){
          $keyadmin = array_keys($staffdetail[0]->getAdministration());
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
        $totalsalairbrutAdm[$x][$i] = "0.00";
        $couttotalpersonemAdm[$x][$i] = "0.00";
        $NetapayerAdm[$x][$i] = "0.00";
        $TotalDecaissementAdm[$x][$i] = "0.00"; 
        $lastDecchargeemployeurhorsgerantAdm[$x][$i] ="0.00";
        $lastDecchargesalarialesAdm[$x][$i] = "0.00";
        $lastDecchargesAdm[$x][$i] = "0.00";
      }}
     
       
         
      for($x = 0 ; $x < $years +1 ; $x++){ 
        for($i =0 ; $i<13 ; $i++){
          $DecchargegerantnonsalarieAdm[$x][$i]="0.00";
          $DecchargeemployeurhorsgerantAdm[$x][$i] = "0.00";
          $DecchargesalarialesAdm[$x][$i] = "0.00";
          $DecchargesAdm[$x][$i] = "0.00";
        }}
        //-------------------------------Fin-----------------------------------------------//
        $years = $businessSession->getNumberofyears();
        if($staffdetail != null){
        $keyadmin = array_keys($staffdetail[0]->getAdministration());
        
       
       for($i =0 ; $i<$years;$i++){
        foreach($staffdetail[$i]->getAdministration() as $key=>$value ){
            $this->ETP[$i][$key] = round(array_sum($value)/12 , 2);}}
        $salairebrut = $staff[0]->getSalairebrut();
       //----------------Debut de calcul de decaissement ----------------------------//
       for($i =0 ; $i<$years;$i++){
       foreach($staffdetail[$i]->getAdministration() as $key=>$value){
          $tvapatronale = $staff[0]->getCharges()[$key][0];
          $chargesalariale = $staff[0]->getCharges()[$key][1];
          $tvaCA = $staff[0]->getPourcentageCA()[$key][0];
          $typeCommision = $staff[0]->getTypecommission()[$key][0];
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
             if($chiffre >0 && $commisionAdm[$i][$position] == 0){
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
            'business'=> $businessSession , 'keyadmin' => $keyadmin,'form'=>$form->createView()
        ,'ETP' => $this->ETP, 'id'=> $id,'salairebrut' => $salairebrut,
        'totalsalairbrutAdm' => $totalsalairbrutAdm, 'couttotalpersonemAdm' => $couttotalpersonemAdm,'NetapayerAdm'=> $NetapayerAdm,
         'TotalDecaissementAdm' =>$TotalDecaissementAdm,'horsgerant' =>$lastDecchargeemployeurhorsgerantAdm,
         'chargesalariale'=> $lastDecchargesalarialesAdm,'charges'=> $lastDecchargesAdm, 'commissionAdm' => $commisionAdm
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
  
                           for($i=0; $i<$years;$i++){
                           $staffdetail[$i]->setAdministration($Administrationdetail[$i]);}
                               
                           //$investments[0]->setBusinessplan($businessSession);
                           }
                    }
                    $entityManager->flush();
            }
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
      $Listproduct =[];
      $years = $businessSession->getNumberofyears();
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
         }
         if(array_key_exists($name, $Productionlist[0]) == true){
          $status = 'Production';
          $ProductionGlobalList = $staff[0]->getProduction() ;
         }
         if(array_key_exists($name, $CommercialList[0]) == true){
          $status = 'Commercial';
          $CommercialGlobalList =  $staff[0]->getSales() ;
         }
         if(array_key_exists($name, $RechercheList[0]) == true){
          $status = 'Recherche';
          $RechercheGlobalList =  $staff[0]->getRecherche() ;
         }
      $JEIstatus = True ;
      $TNSstatus = True ;
      $GRAstatus = True ;
      $salairebrut = $staff[0]->getSalairebrut();
      $condition = $staff[0]->getConditions()[$name];
      $charges =  $staff[0]->getCharges()[$name];
      $Listcharges = $staff[0]->getCharges();
      $ListConditions = $staff[0]->getConditions();
      $TypeCommission  = $staff[0]->getTypecommission()[$name]; 
      $CA = $staff[0]->getPourcentageCA()[$name];
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
     // dump($JEI);die();
      $form = $this->createForm(StaffEditFormType::class,['Department' => '0' ,'JEI' => $JEIstatus 
      ,'TNS'=> $TNSstatus , 'Gratification'=>$GRAstatus ,'Typecommision' =>$TypeCommission[0] ,
      ]);
      
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){
       
            ${"admin" . $name}= [];
            if($status == 'Administration' && $AdminGlobalList != []){
             ${"admin" . $name}=$AdminGlobalList[$name]; // conserver les valeurs  avant de supprimer
             ${"salaire".$name}= $salairebrut[$name];
             
             //unset($AdminGlobalList[$name]);
             
             unset($salairebrut[$name]);
             unset($Listcharges[$name]);
             unset($ListConditions[$name]);
             // $staff[0]->setAdministration($AdminGlobalList);
              $staff[0]->setSalairebrut($salairebrut);
              $staff[0]->setCharges($Listcharges);
              $staff[0]->setConditions($ListConditions);
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
              }}
              $entityManager->flush();
        return $this->redirectToRoute('staff');
      }


      return $this->render('staff/edit.html.twig',[
      'name'=> $name , 'charges' => $charges,'Listproduct' => $Listproduct, 'CA' =>  $CA,
      'form'=>$form->createView() , 'product' => $Listproduct,
      ]);
    }
}
