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
use App\Entity\Investments;
use App\Entity\Investmentsdetail;
/**
* @Route("/{_locale}/dashboard/my-business-plan/Depreciation")
 */
class DepreciationController extends AbstractController
{
  private $listGlobalAdm ;
  private $listGlobalPro ;
  private $listGlobalCom ;
  private $listGlobalRec ;
  private $GlobalAdm;
  private $GlobalPro;
  private $GlobalCom; 
  private $GlobalRec;
  private $totalFinalSumListperName;
  private $totalFinalSumListperNamePro;
  private $totalFinalSumListperNameCom;
  private $totalFinalSumListperNameRec;
  private $Sommetotal;
  private $SommetotalPro;
  private $SommetotalCom;
  private $SommetotalRec;
  private $categorie;
  static $listdepreciation ;
  static $depreciationAdmG ;
  static $depreciationAdmD ;

  static $depreciationProG ;
  static $depreciationProD ;

  static $depreciationComG ;
  static $depreciationComD ;

  static $depreciationRDG ;
  static $depreciationRDD ; 

  static $depreciationdetailAdm ;
  static $depreciationdetailPro ;
  static $depreciationdetailCom ;
  static $depreciationdetailRD ;
     /**
     * @Route("/", name="depreciation")
     */
   public function index(){
    $entityManager = $this->getDoctrine()->getManager();
    $businessSession =$this->container->get('session')->get('business');
    $years = $businessSession->getNumberofyears();
    $rangeofdetail = $businessSession->getRangeofdetail();
    $rangeofglobal = $years  - $rangeofdetail ;
    $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
    $investmentsdetail = $entityManager->getRepository(Investmentsdetail::class)->findBy(['Investment' => $investments]);
    $L= [];
    $type=[];
    $TOTAL =[];
    if($investments!= null){
      $keysAdministration =array_keys($investmentsdetail[0]->getAdministration());
      $keysProduction = array_keys($investmentsdetail[0]->getProduction());
      $KeyCommercial = array_keys($investmentsdetail[0]->getSales());
      $keyRecherche = array_keys($investmentsdetail[0]->getRecherche());
    }
      else{
        $keysAdministration =[];
        $keysProduction =[];
        $KeyCommercial = [];
        $keyRecherche =[];
      
      }
    for($i =0 ; $i< $years ; $i++){
    $this->detail($i);}
    //dump($investmentsdetail[0]->getAdministration());die();
    //-------------------global list-------------------------//
 
    //----------------------------------------------//
     //-------------------Detailled list-------------------------//
    
      //----------------------------------------------//
    //dump($newAdministrationdetail);die();
    foreach($this->categorie as $key => $value){
      $L[$value[0]] = [];
       
    }
    foreach($this->categorie as $key => $value){
      array_push($L[$value[0]],$key);
    }
    foreach($keysAdministration as $key=>$value){
      
      $type[$value] = 'Administration';
    }
    foreach($keysProduction as $key=>$value){
      $type[$value] = 'Production';
    }
    foreach($KeyCommercial as $key=>$value){
      $type[$value] = 'Commercial';
    }
    foreach($keyRecherche as $key=>$value){
      $type[$value] = 'Recherche';
    }
    if($this->Sommetotal != [] ){
    for($i =0 ;$i<$years ;$i++){
    $TOTAL[$i] = $this->Sommetotal[$i] + $this->GlobalAdm[$i] +$this->SommetotalPro [$i] + $this->GlobalPro[$i] + $this->SommetotalCom[$i] + $this->GlobalCom[$i]   + $this->SommetotalRec[$i] + $this->GlobalRec[$i]; 
    }}
    self::$listdepreciation =$TOTAL ;
    self::$depreciationAdmD = $this->Sommetotal  ;
    self::$depreciationProD = $this->SommetotalPro  ;
    self::$depreciationComD = $this->SommetotalCom  ;
    self::$depreciationRDD = $this->SommetotalRec  ;

    self::$depreciationAdmG = $this->GlobalAdm  ;
    self::$depreciationProG = $this->GlobalPro  ;
    self::$depreciationComG = $this->GlobalCom  ;
    self::$depreciationRDG = $this->GlobalRec  ;

 
    
    return $this->render('depreciation/index.html.twig',['business'=> $businessSession, 'rangeofglobal'=>$rangeofglobal,
    'totalperName' => $this->totalFinalSumListperName,'totalperNamePro' => $this->totalFinalSumListperNamePro ,'totalperNameCom' => $this->totalFinalSumListperNameCom ,'totalperNameRec' => $this->totalFinalSumListperNameRec , 
     'categorie' => $this->categorie,'nature'=>$L,'type' => $type,
    'Administration' => $keysAdministration,'Production' => $keysProduction,'Commercial' => $KeyCommercial,'Recherche'=> $keyRecherche,
    'sumperyear' =>$this->Sommetotal,'sumperyearPro' =>$this->SommetotalPro,'sumperyearCom' =>$this->SommetotalCom,'sumperyearRec' =>$this->SommetotalRec,
    'GlobalListAdm' => $this->listGlobalAdm ,'GlobalListPro' => $this->listGlobalPro,'GlobalListCom' => $this->listGlobalCom,'GlobalListRec' => $this->listGlobalRec
    ,'SumglobalAdm' => $this->GlobalAdm , 'SumglobalPro' => $this->GlobalPro , 'SumglobalCom' => $this->GlobalCom , 'SumglobalRec' => $this->GlobalRec  
    ]);
   }
   public function getdepreciation(){
    
    return self::$listdepreciation;
  }
  public function getdepAdmG(){
    return self::$depreciationAdmG;
  }
  public function getdepProG(){
    return self::$depreciationProG;
  }
  public function getdepComG(){
    return self::$depreciationComG;
  }
  public function getdepRDG(){
    return self::$depreciationRDG;
  }
  public function getdepAdmD(){
    return self::$depreciationAdmD;
  }
  public function getdepProD(){
    return self::$depreciationProD;
  }
  public function getdepComD(){
    return self::$depreciationComD;
  }
  public function getdepRDD(){
    return self::$depreciationRDD;
  }

  public function getdeplistAdm(){
    return self::$depreciationdetailAdm;

  }
  public function getdeplistPro(){
    return self::$depreciationdetailPro;
  }
  public function getdeplistCom(){
    return self::$depreciationdetailCom;
  }
  public function getdeptlistRec(){
    return self::$depreciationdetailRD;
  }

   /**
     * @Route("-year-{id}", name="depreciationdetail")
     */
    public function detail($id){
      $entityManager = $this->getDoctrine()->getManager();
      $businessSession =$this->container->get('session')->get('business');
      $years = $businessSession->getNumberofyears();
      $rangeofdetail = $businessSession->getRangeofdetail();
      $rangeofglobal = $years  - $rangeofdetail ;
      $investments = $entityManager->getRepository(Investments::class)->findByBusinessplan($businessSession);
      $investmentsdetail = $entityManager->getRepository(Investmentsdetail::class)->findBy(['Investment' => $investments ]);
      //dump($investments,$investmentsdetail);die();

      //----------------------------verifier l'exsistance de l'investement et intitialiser les variable --//
      if($investments!= null){
      $SumListAdm = [];$SumListPro = []; $SumListCom= []; $SumListRec = [];
      //$FinalPro = [];
      $keysAdministration =array_keys($investmentsdetail[0]->getAdministration());
      $keysProduction = array_keys($investmentsdetail[0]->getProduction());
      $KeyCommercial = array_keys($investmentsdetail[0]->getSales());
      $keyRecherche = array_keys($investmentsdetail[0]->getRecherche());
      $this->categorie = $investments[0]->getCategorie();}
      else{
        $keysAdministration =[];
        $keysProduction =[];
        $KeyCommercial = [];
        $keyRecherche =[];
        $this->categorie = [];
        
      }
      $var = 0 ; 
      //----------------------------------Initialiser les listes globale-----------------//
      foreach($keysAdministration as $value){
        ${'Globalelement'.$value}  =0;
        for($x = 0 ; $x<$rangeofglobal+30;$x++){
          $this->listGlobalAdm[$value][$x]= 0;
        }
      } 
      foreach($keysProduction as $value){
        for($x = 0 ; $x<$rangeofglobal+30;$x++){
          $this->listGlobalPro[$value][$x]= 0;
        }
      } 
      foreach($KeyCommercial as $value){
        for($x = 0 ; $x<$rangeofglobal+30;$x++){
          $this->listGlobalCom[$value][$x]= 0;
        }
      } 
      foreach($keyRecherche as $value){
        for($x = 0 ; $x<$rangeofglobal+30;$x++){
          $this->listGlobalRec[$value][$x]= 0;
        }
      } 
      for($x = 0 ; $x<$years+10;$x++){
        $this->GlobalAdm[$x]= 0;
        $this->GlobalPro[$x]= 0;
        $this->GlobalCom[$x]= 0;
        $this->GlobalRec[$x]= 0;
      }
      //---------initialisation des listes necessaire  (Seulment pour le traitement detailler)-------------------//
      for($i = 0 ; $i<$years;$i++) {
        for($x = 0 ; $x<12;$x++){
          $FinalAdm[$i][$x] = 0 ;
          $FinalPro[$i][$x] = 0 ;
          $FinalCom[$i][$x] = 0 ;
          $FinalRec[$i][$x] = 0 ;
          $FinalShowed[$i][$x]=0;
          $FinalShowedPro[$i][$x]=0;
          $FinalShowedCom[$i][$x]=0;
          $FinalShowedRec[$i][$x]=0;
        }
      }
     
      foreach($keysAdministration as $value){
      for($i=0 ; $i<300;$i++){
        ${'list'.$value}[$i] = 0; //
        ${'listshowed'.$value}[$i] =0; //     
        $SumListAdm[$i] = 0 ; 
        }
      }
      
      foreach($keysProduction as $value){
        for($i=0 ; $i<300;$i++){
          ${'list'.$value}[$i] = 0; //
          ${'listshowed'.$value}[$i] =0; //     
          $SumListPro[$i] = 0 ; 
          }
        }
        foreach($KeyCommercial as $value){
          for($i=0 ; $i<300;$i++){
            ${'list'.$value}[$i] = 0; //
            ${'listshowed'.$value}[$i] =0; //     
            $SumListCom[$i] = 0 ; 
            }
          }
        foreach($keyRecherche as $value){
            for($i=0 ; $i<300;$i++){
              ${'list'.$value}[$i] = 0; //
              ${'listshowed'.$value}[$i] =0; //     
              $SumListRec[$i] = 0 ; 
              }
            }
          //----------fin-------------------------------------------------//
          if($investments!= null){
      //------------------- List TVA  & Duree -------------------------//
      $TVAAdministration =  array_intersect_key($investments[0]->getTvalist(),$investmentsdetail[0]->getAdministration());
      $TVAProduction =      array_intersect_key($investments[0]->getTvalist(),$investmentsdetail[0]->getProduction());
      $TVACommercial =      array_intersect_key($investments[0]->getTvalist(),$investmentsdetail[0]->getSales());
      $TVARecherche =       array_intersect_key($investments[0]->getTvalist(),$investmentsdetail[0]->getRecherche());
      $DureeAdministration = array_intersect_key($investments[0]->getDuration(),$investmentsdetail[0]->getAdministration());
      $DureeProduction =  array_intersect_key($investments[0]->getDuration(),$investmentsdetail[0]->getProduction());
      $DureeCommercial =  array_intersect_key($investments[0]->getDuration(),$investmentsdetail[0]->getSales());
      $DureeRecherche  =  array_intersect_key($investments[0]->getDuration(),$investmentsdetail[0]->getRecherche());
      //-------------------------------------------------------------------//
      //----------------------Listes pour le traitement Globale--------------------------//
       $GlobalAdministration = $investments[0]->getAdministration();
       $GlobalProduction = $investments[0]->getProduction();
       $GlobalCommercial = $investments[0]->getSales();
       $GlobalRecherche = $investments[0]->getRecherche();
      
      //------------------Calcul d'amortissement pour la saisie detaillee----------------------------------//
      
      for($x = 0 ; $x < $years; $x++){
      foreach($investmentsdetail[$x]->getAdministration() as $key=>$value){
        ${'element'.$key}[$x] =0 ;
      foreach($value as $position=>$chiffre){
        //calculer le nombre d'element positive
        if($chiffre>0){
          ${'element'.$key}[$x]++;
        } 
      $valueperyear = floatval($chiffre)/ floatval($DureeAdministration[$key][0]);
      $valuepermonth = floatval($valueperyear)/12;   
        for($i=$position +($x*12) ; $i<(12*floatval($DureeAdministration[$key][0]))+$position + ($x*12);$i++){
          ${'list'.$key}[$i] +=  round($valuepermonth, 2);}
      //dump(round($valuepermonth, 2));die();
      //dump(number_format((float)$valueperyear, 2, '.', ''));die();
     }}
    }
    //----------------------Calcul d'amortissement pour le saisie Globale--------------------//
   
    foreach($GlobalAdministration as $name=>$value){
      ${'Globalelement'.$name} = 0;
      foreach($value as $position=>$chiffre){
      $valueperyear = floatval($chiffre)/ floatval($DureeAdministration[$name][0]);
      ${'Globalelement'.$name} +=$chiffre ;
      for($i=$position; $i<floatval($DureeAdministration[$name][0])+$position;$i++){
      
        $this->listGlobalAdm[$name][$i]+=$valueperyear;
       
      }
    }}
    
    foreach($GlobalProduction as $name=>$value){
      ${'Globalelement'.$name} = 0;
      foreach($value as $position=>$chiffre){
      $valueperyear = floatval($chiffre)/ floatval($DureeProduction[$name][0]);
      ${'Globalelement'.$name} +=$chiffre ;
      for($i=$position; $i<floatval($DureeProduction[$name][0])+$position;$i++){
      
        $this->listGlobalPro[$name][$i]+=$valueperyear;
       
      }
    }}
    foreach($GlobalCommercial as $name=>$value){
      ${'Globalelement'.$name} = 0;
      foreach($value as $position=>$chiffre){
      $valueperyear = floatval($chiffre)/ floatval($DureeCommercial[$name][0]);
      ${'Globalelement'.$name} +=$chiffre ;
      for($i=$position; $i<floatval($DureeCommercial[$name][0])+$position;$i++){
      
        $this->listGlobalCom[$name][$i]+=$valueperyear;
       
      }
    }}
    foreach($GlobalRecherche as $name=>$value){
      ${'Globalelement'.$name} = 0;
      foreach($value as $position=>$chiffre){
      $valueperyear = floatval($chiffre)/ floatval($DureeRecherche[$name][0]);
      ${'Globalelement'.$name} +=$chiffre ;
      for($i=$position; $i<floatval($DureeRecherche[$name][0])+$position;$i++){
      
        $this->listGlobalRec[$name][$i]+=$valueperyear;
       
      }
    }}
    //dump($this->listGlobalAdm);die();
    //-----------------------------------------------------------------------//
    for($x = 0 ; $x < $years; $x++){
      foreach($investmentsdetail[$x]->getProduction() as $key=>$value){
        ${'element'.$key}[$x] =0 ;
      foreach($value as $position=>$chiffre){
        if($chiffre>0){
          ${'element'.$key}[$x]++;
        } 
      $valueperyear = floatval($chiffre)/ floatval($DureeProduction[$key][0]);
      $valuepermonth = floatval($valueperyear)/12;   
        for($i=$position +($x*12) ; $i<(12*floatval($DureeProduction[$key][0]))+$position + ($x*12);$i++){
          ${'list'.$key}[$i] +=  round($valuepermonth, 2);}
      //dump(round($valuepermonth, 2));die();
      //dump(number_format((float)$valueperyear, 2, '.', ''));die();
     }}
    }
     //----------------------------------------------------------------------//
     for($x = 0 ; $x < $years; $x++){
      foreach($investmentsdetail[$x]->getSales() as $key=>$value){
        ${'element'.$key}[$x] =0 ;
      foreach($value as $position=>$chiffre){
        if($chiffre>0){
          ${'element'.$key}[$x]++;
        } 
      $valueperyear = floatval($chiffre)/ floatval($DureeCommercial[$key][0]);
      $valuepermonth = floatval($valueperyear)/12;   
        for($i=$position +($x*12) ; $i<(12*floatval($DureeCommercial[$key][0]))+$position + ($x*12);$i++){
          ${'list'.$key}[$i] +=  round($valuepermonth, 2);}
      //dump(round($valuepermonth, 2));die();
      //dump(number_format((float)$valueperyear, 2, '.', ''));die();
     }}
    }
    //-------------------------------------------------------------------------//
    for($x = 0 ; $x < $years; $x++){
      foreach($investmentsdetail[$x]->getRecherche() as $key=>$value){
        ${'element'.$key}[$x] =0 ;
      foreach($value as $position=>$chiffre){
        if($chiffre>0){
          ${'element'.$key}[$x]++;
        } 
      $valueperyear = floatval($chiffre)/ floatval($DureeRecherche[$key][0]);
      $valuepermonth = floatval($valueperyear)/12;   
        for($i=$position +($x*12) ; $i<(12*floatval($DureeRecherche[$key][0]))+$position + ($x*12);$i++){
          ${'list'.$key}[$i] +=  round($valuepermonth, 2);}
      //dump(round($valuepermonth, 2));die();
      //dump(number_format((float)$valueperyear, 2, '.', ''));die();
     }}
    }
    //---------------------------------------------------------------------------//
    $pos= 0;
    $diff = 0 ;
    foreach($keysAdministration as $value){
     
     $max =  max(${'list'.$value});// chercher la position de maximum
     for($i=0 ; $i<count(${'list'.$value})-1;$i++){
     if(${'list'.$value}[$i]==$max){
     ${'pos'.$value}=$i;
     if(${'pos'.$value}>0){
      $diff = ${'list'.$value}[${'pos'.$value}] - ${'list'.$value}[${'pos'.$value} - 1];
     }
     //$diff = ${'list'.$value}[$pos -1  ] + ${'list'.$value}[$pos];
    break;
     }}
  
      if(array_sum(${'element'.$value})>1 && ${'Globalelement'.$value}==0){
     for($i=0 ; $i<${'pos'.$value};$i++){// annuler les premiers chiffres
      ${'listshowed'.$value}[$i]= 0; 
     }
     for($i = ${'pos'.$value} ; $i<count(${'list'.$value})-1;$i++){// 
     if(${'list'.$value}[$i]==0){
      ${'listshowed'.$value}[$i] = 0;
     }
     else{
      ${'listshowed'.$value}[$i]= $diff; 
     }   }  }
    else{
      for($i = 0 ; $i<count(${'list'.$value})-1;$i++){
        ${'listshowed'.$value}[$i] = 0;
      }
    }

    }
    //------------------------------------------------------------------------//
    $pos= 0;
    $diff = 0 ;
    foreach($keysProduction as $value){
     $max =  max(${'list'.$value});// chercher la position de maximum
     for($i=0 ; $i<count(${'list'.$value})-1;$i++){
     if(${'list'.$value}[$i]==$max){
     ${'pos'.$value}=$i;
     if(${'pos'.$value}>0){
      $diff = ${'list'.$value}[${'pos'.$value}] - ${'list'.$value}[${'pos'.$value} - 1];
     } 
     //$diff = ${'list'.$value}[$pos -1  ] + ${'list'.$value}[$pos];
    break;
     }}
  
       
     for($i=0 ; $i<${'pos'.$value};$i++){// annuler les premiers chiffres
      ${'listshowed'.$value}[$i]= 0; 
     }
     for($i = ${'pos'.$value} ; $i<count(${'list'.$value})-1;$i++){// 
     if(${'list'.$value}[$i]==0){
      ${'listshowed'.$value}[$i] = 0;
     }
     else{
      ${'listshowed'.$value}[$i]= $diff; 
     }   }}
    //-----------------------------------------------------------------------//
    $pos= 0;
    $diff = 0 ;
    foreach($KeyCommercial as $value){
     $max =  max(${'list'.$value});// chercher la position de maximum
     for($i=0 ; $i<count(${'list'.$value})-1;$i++){
     if(${'list'.$value}[$i]==$max){
     ${'pos'.$value}=$i;
     if(${'pos'.$value}>0){
      $diff = ${'list'.$value}[${'pos'.$value}] - ${'list'.$value}[${'pos'.$value} - 1];
     } 
     //$diff = ${'list'.$value}[$pos -1  ] + ${'list'.$value}[$pos];
    break;
     }}
  
       
     for($i=0 ; $i<${'pos'.$value};$i++){// annuler les premiers chiffres
      ${'listshowed'.$value}[$i]= 0; 
     }
     for($i = ${'pos'.$value} ; $i<count(${'list'.$value})-1;$i++){// 
     if(${'list'.$value}[$i]==0){
      ${'listshowed'.$value}[$i] = 0;
     }
     else{
      ${'listshowed'.$value}[$i]= $diff; 
     }   }}
     //----------------------------------------------------------------//
     $pos= 0;
     $diff = 0 ;
     foreach($keyRecherche as $value){
      $max =  max(${'list'.$value});// chercher la position de maximum
      for($i=0 ; $i<count(${'list'.$value})-1;$i++){
      if(${'list'.$value}[$i]==$max){
      ${'pos'.$value}=$i;
      if(${'pos'.$value}>0){
       $diff = ${'list'.$value}[${'pos'.$value}] - ${'list'.$value}[${'pos'.$value} - 1];
      } 
      //$diff = ${'list'.$value}[$pos -1  ] + ${'list'.$value}[$pos];
     break;
      }}
   
        
      for($i=0 ; $i<${'pos'.$value};$i++){// annuler les premiers chiffres
       ${'listshowed'.$value}[$i]= 0; 
      }
      for($i = ${'pos'.$value} ; $i<count(${'list'.$value})-1;$i++){// 
      if(${'list'.$value}[$i]==0){
       ${'listshowed'.$value}[$i] = 0;
      }
      else{
       ${'listshowed'.$value}[$i]= $diff; 
      }   }}
    //---------------------calculer de la somme-------------------//
    foreach($keysAdministration as $value){
     for ($i =0 ; $i< count($SumListAdm);$i++){
      $SumListAdm[$i] += ${'list'.$value}[$i] ;
      $SumListperNameAdm[$value][$i]=${'list'.$value}[$i] ;
    }}
    
    //-------------------------------------------//
    foreach($keysProduction as $value){
      for ($i =0 ; $i< count($SumListPro);$i++){
       $SumListPro[$i] += ${'list'.$value}[$i] ;
       $SumListperNamePro[$value][$i]=${'list'.$value}[$i] ;
     }}
    //-------------------------------------------//
    foreach($KeyCommercial as $value){
      for ($i =0 ; $i< count($SumListCom);$i++){
       $SumListCom[$i] += ${'list'.$value}[$i] ;
       $SumListperNameCom[$value][$i]=${'list'.$value}[$i] ;
     }}
    //--------------------------------------------------//
    foreach($keyRecherche as $value){
      for ($i =0 ; $i< count($SumListRec);$i++){
       $SumListRec[$i] += ${'list'.$value}[$i] ;
       $SumListperNameRec[$value][$i]=${'list'.$value}[$i] ;
     }}
    //------------------------------------------------//
    //---------------------fin somme--------------------------//
    //--------------------repartition ------------------------//
    $month =0 ;
    $year = 0 ;
     for($x=0 ; $x<$years*12;$x++){
      if($SumListAdm != []){                       
     $FinalAdm[$year][$month] = $SumListAdm[$x];} // liste total Administration 
      if($SumListPro != []){
     $FinalPro[$year][$month] = $SumListPro[$x];} // liste total Production
      if($SumListCom != []){
     $FinalCom[$year][$month] = $SumListCom[$x];} // liste total Commercial
      if($SumListRec != []){
     $FinalRec[$year][$month] = $SumListRec[$x];} // liste total Recherche
     $month++;
     if($month == 12 ){
      $year++;
      $month=0 ;  } }
     //---------------------------------------------------------------//
    foreach($keysAdministration as $value){
      $M =0 ;
      $Y = 0 ;
        for($x=0 ; $x<$years*12;$x++){                       
          $FinalShowed[$value][$Y][$M] = ${'listshowed'.$value}[$x]; // liste a afficher 
          $FinalSumListperName[$value][$Y][$M] =  $SumListperNameAdm[$value][$x];
          $M++;
          if($M == 12 ){
           $Y++;
           $M=0 ;  } }
      }
      //-----------------------------------------------------------------//
      foreach($keysProduction as $value){
        $M =0 ;
        $Y = 0 ;
          for($x=0 ; $x<$years*12;$x++){                       
            $FinalShowedPro[$value][$Y][$M] = ${'listshowed'.$value}[$x]; // liste a afficher 
            $FinalSumListperNamePro[$value][$Y][$M] =  $SumListperNamePro[$value][$x];
            $M++;
            if($M == 12 ){
             $Y++;
             $M=0 ;  } }
        }
     //-------------------------------------------------------------------------//
     foreach($KeyCommercial as $value){
      $M =0 ;
      $Y = 0 ;
        for($x=0 ; $x<$years*12;$x++){                       
          $FinalShowedCom[$value][$Y][$M] = ${'listshowed'.$value}[$x]; // liste a afficher 
          $FinalSumListperNameCom[$value][$Y][$M] =  $SumListperNameCom[$value][$x];
          $M++;
          if($M == 12 ){
           $Y++;
           $M=0 ;  } }
      }
      //-----------------------------------------------------------------------//
      foreach($keyRecherche as $value){
        $M =0 ;
        $Y = 0 ;
          for($x=0 ; $x<$years*12;$x++){                       
            $FinalShowedRec[$value][$Y][$M] = ${'listshowed'.$value}[$x]; // liste a afficher 
            $FinalSumListperNameRec[$value][$Y][$M] =  $SumListperNameRec[$value][$x];
            $M++;
            if($M == 12 ){
             $Y++;
             $M=0 ;  } }
        }

     //dump($listshowedahmed,$FinalShowed);die();
     //--------------------Fin--------------------------------//
     //--------------------Somme par liste --------------------//
     for($x=0 ; $x<$years;$x++){
      $this->Sommetotal[$x] = array_sum($FinalAdm[$x]);
      $this->SommetotalPro[$x] = array_sum($FinalPro[$x]);
      $this->SommetotalCom[$x] = array_sum($FinalCom[$x]);
      $this->SommetotalRec[$x] = array_sum($FinalRec[$x]);
     }
     for($x=0 ; $x<$years;$x++){ // Somme de liste Globale
      if($this->listGlobalAdm!=null){
     foreach($this->listGlobalAdm as $key=>$value){
      $this->GlobalAdm[$x+$rangeofdetail] += $value[$x]; }}
      if($this->listGlobalPro!=null){
     foreach($this->listGlobalPro as $key=>$value){
      $this->GlobalPro[$x+$rangeofdetail] += $value[$x]; }}
      if($this->listGlobalCom!=null){
     foreach($this->listGlobalCom as $key=>$value){
      $this->GlobalCom[$x+$rangeofdetail] += $value[$x]; }}
      if($this->listGlobalRec!=null){
     foreach($this->listGlobalRec as $key=>$value){
      $this->GlobalRec[$x+$rangeofdetail] += $value[$x]; }}
    }
    
     foreach($keysAdministration as $value){
      for($x=0 ; $x<$years;$x++){
        $this->totalFinalSumListperName[$value][$x] = array_sum($FinalSumListperName[$value][$x]);
      }}
    //----------------------------------------------------------//
    foreach($keysProduction as $value){
      for($x=0 ; $x<$years;$x++){
        $this->totalFinalSumListperNamePro[$value][$x] = array_sum($FinalSumListperNamePro[$value][$x]);
      }}
    //--------------------------------------------------------//
    foreach($KeyCommercial as $value){
      for($x=0 ; $x<$years;$x++){
        $this->totalFinalSumListperNameCom[$value][$x] = array_sum($FinalSumListperNameCom[$value][$x]);
      }}
    //------------------------------------------------------------------//
    foreach($keyRecherche as $value){
      for($x=0 ; $x<$years;$x++){
        $this->totalFinalSumListperNameRec[$value][$x] = array_sum($FinalSumListperNameRec[$value][$x]);
      }}
     //--------------------Fin--------------------------------//
     //--------------------Fin------------------------------------//
    }
    else{
      $FinalAdm= [];
      $FinalPro=[];
      $FinalCom=[];
      $FinalRec=[];
      $FinalShowed=[];
      $this->Sommetotal = [];
    }
    self::$depreciationdetailAdm = $FinalAdm ;
    self::$depreciationdetailPro = $FinalPro ;
    self::$depreciationdetailCom = $FinalCom ;
    self::$depreciationdetailRD = $FinalRec ;
 //dump( $SumListperName,$FinalSumListperName,$totalFinalSumListperName);die();
      return $this->render('depreciation/detail.html.twig',['business'=> $businessSession,'id'=>$id,
     'sumtotal' => $FinalAdm,'sumtotalPro'=>$FinalPro,'sumtotalCom'=>$FinalCom,'sumtotalRec'=>$FinalRec, 'showed' => $FinalShowed,'showedpro' => $FinalShowedPro,'showedcom' => $FinalShowedCom, 'showedrec' => $FinalShowedRec
     ,'Administration' => $keysAdministration, 'Production' => $keysProduction, 'Commercial'=> $KeyCommercial,'Recherche'=>$keyRecherche,
     'categorie' => $this->categorie, 'sumperyear' =>$this->Sommetotal,'sumperyearPro' =>$this->SommetotalPro,
     'sumperyearCom' =>$this->SommetotalCom,'sumperyearRec' =>$this->SommetotalRec, 'totalperName' => $this->totalFinalSumListperName,
     'totalperNamePro' => $this->totalFinalSumListperNamePro , 'totalperNameCom' => $this->totalFinalSumListperNameCom
     ,'totalperNameRec' => $this->totalFinalSumListperNameRec
     
     ]);
     }
}