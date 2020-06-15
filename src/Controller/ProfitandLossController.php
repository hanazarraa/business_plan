<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SalesRepository;
use App\Repository\SalesdetailledRepository;
use App\Repository\GeneralexpensesRepository;
use App\Repository\ProductRepository;
use App\Repository\LoansRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
/**
     * @Route("/{_locale}/dashboard/my-business-plan/profitandloss")
 */
class ProfitandLossController extends AbstractController
{
     private $totalCA ;
     private $SumfinalCAperMouth;
     private $purchase ;
     private $purchasestatic ;
     private $staff;
     private $staffAdm ;
     private $staffPro ;
     private $staffCom ;
     private $staffRD;
     private $generalexpense;
     private $generalexpenseAdmG;
     private $generalexpenseComG;
     private $generalexpenseRDG;
     private $generalexpenseAdmD ;
     private $generalexpenseComD;

     private $generalexpenselistAdm;
     private $generalexpenselistPro;
     private $generalexpenselistCom;
     private $generalexpenselistRD;

     private $generalexpenselist;
     private $fraisAdm;
     private $fraisPro;
     private $fraisCom;
     private $fraisRD ;

     private $depreciation;
     private $depreciationAdmG ;
     private $depreciationComG ; 
     private $depreciationRDG;

     private $depreciationlistAdm;
     private $depreciationlistPro ;
     private $depreciationlistCom;
     private $depreciationlistRD;


     private $amortissementAdm;
     private  $amortissementCom;
     private $amortissementRD;
     private $fraisfinancier ;

     private $session;

     public function __construct(SessionInterface $session)
     {
         $this->session = $session;
     }
    /**
     * @Route("/", name="profitandloss")
     */
    public function index(Request $request ,ProductRepository $productRepository ,SalesRepository $SalesRepository,SalesdetailledRepository $SalesdetailledRepository,GeneralexpensesRepository $generalrep,LoansRepository $loansrepository)
    {
        $businessSession =$this->container->get('session')->get('business');
        $years = $businessSession->getNumberofyears();  
        $rangeofdetail = $businessSession->getRangeofdetail(); 
        $response = $this->forward('App\Controller\SalesController::sales', [
            'request'  => $request,
            'productRepository' => $productRepository,
            'SalesRepository' => $SalesRepository,
        ]); 
        $response = $this->forward('App\Controller\PurchaseController::index', [
            'SalesdetailledRepository'  => $SalesdetailledRepository,
            'productRepository' => $productRepository,
            'request'  => $request,
            'SalesRepository' => $SalesRepository,
        ]);
        $response = $this->forward('App\Controller\StaffController::index', [
            'request'  => $request,
            'productRepository' => $productRepository,
            'SalesRepository' => $SalesRepository,
                
        ]);
        
        $response = $this->forward('App\Controller\GeneralexpensesController::index', [
            'request'  => $request,
            'GeneralexpensesRepository'  => $generalrep,
                
        ]);
        $response = $this->forward('App\Controller\LoansController::index', [
            'LoansRepository'  => $loansrepository,
            'Request'  => $request,
                
        ]);
        $response = $this->forward('App\Controller\DepreciationController::index', []);
        $finalCA = SalesController::getfinalca(); 
        $this->purchase = PurchaseController::getlistpurchase();
        $this->purchasestatic = PurchaseController::getpurchasedetail();
        $this->staff = StaffController::getstaff();
        $this->generalexpense = GeneralexpensesController::getpurchase();
        $generalexpenseAdmG = GeneralexpensesController::getfraisAdmG();
        $generalexpenseProG = GeneralexpensesController::getfraisProG();
        $generalexpenseComG = GeneralexpensesController::getfraisComG();
        $generalexpenseRDG = GeneralexpensesController::getfraisRDG();

        $generalexpenseAdmD = GeneralexpensesController::getfraisAdmD();
        $generalexpenseProD = GeneralexpensesController::getfraisProD();
        $generalexpenseComD = GeneralexpensesController::getfraisComD();
        $generalexpenseRDD = GeneralexpensesController::getfraisRDD();
      

        $this->depreciation = DepreciationController::getdepreciation();
        $depreciationAdmG = DepreciationController::getdepAdmG();
        $depreciationProG = DepreciationController::getdepProG();
        $depreciationComG = DepreciationController::getdepComG();
        $depreciationRDG = DepreciationController::getdepRDG();

        $depreciationAdmD = DepreciationController::getdepAdmD();
        $depreciationProD = DepreciationController::getdepProD();
        $depreciationComD = DepreciationController::getdepComD();
        $depreciationRDD = DepreciationController::getdepRDD();
       
        $this->depreciationlistAdm = DepreciationController::getdeplistAdm();
        $this->depreciationlistPro = DepreciationController::getdeplistPro();
        $this->depreciationlistCom = DepreciationController::getdeplistCom();
        $this->depreciationlistRec = DepreciationController::getdeptlistRec();



        $this->staffAdm = StaffController::getstaffAdm();
        $this->staffPro = StaffController::getstaffPro();
        $this->staffCom = StaffController::getstaffCom();
        $this->staffRD = StaffController::getstaffRD();

        $this->fraisfinancier = LoansController::getfraisfinancier();
       
       
        for($x = 0 ; $x < $years ; $x++){
            for($i = 0 ; $i < 12 ; $i++){
              $this->SumfinalCAperMouth[$x][$i] =  0 ;}} 
        foreach($finalCA as $key=>$value){
            for($x = 0 ; $x < $years ; $x++){
            for($i = 0 ; $i < 12 ; $i++){
               $this->SumfinalCAperMouth[$x][$i] +=  $finalCA[$key][$x][$i] ;}}}
          
            for($x = 0 ; $x < $years ; $x++){
                $this->totalCA[$x] = array_sum($this->SumfinalCAperMouth[$x]);}
                
        //-------------------------generalexpenses        
        $pos = 0 ;
        for ($i=0 ; $i<$years;$i++){
        if($pos < $rangeofdetail){
            $this->fraisAdm[$i] = $generalexpenseAdmD[$i];
            $this->fraisPro[$i] = $generalexpenseProD[$i];
            $this->fraisCom[$i] = $generalexpenseComD[$i];
            $this->fraisRD[$i] = $generalexpenseRDD[$i];
            $pos++ ;
        }
        
        else if ($pos >= $rangeofdetail ){
            $this->fraisAdm[$i] = $generalexpenseAdmG[$i - $rangeofdetail];
            $this->fraisPro[$i] = $generalexpenseProG[$i - $rangeofdetail];
            $this->fraisCom[$i] = $generalexpenseComG[$i - $rangeofdetail];
            $this->fraisRD[$i] = $generalexpenseRDG[$i - $rangeofdetail];
            $pos++ ;
        }}
        //-------------------------depreciation-------------------
        for($i=0 ; $i<$years;$i++){
            $this->amortissementAdm[$i] ="0.00";
            $this->amortissementPro[$i] ="0.00";
            $this->amortissementCom[$i] ="0.00";
            $this->amortissementRD[$i] ="0.00";
        }
       if($depreciationAdmD != []){
        $pos = 0 ;
        for ($i=0 ; $i<$years;$i++){
    
            $this->amortissementAdm[$i] += $depreciationAdmD[$i] + $depreciationAdmG[$i];
            $this->amortissementPro[$i] += $depreciationProD[$i] + $depreciationProG[$i];
            $this->amortissementCom[$i] += $depreciationComD[$i] + $depreciationComG[$i];
            $this->amortissementRD[$i] += $depreciationRDD[$i] + $depreciationRDG[$i];      
    }}
    if ($this->staffPro == null){
        for ($i=0 ; $i<$years;$i++){
        $this->staffAdm[$i] ="0.00";
        $this->staffPro[$i] ="0.00";
        $this->staffCom[$i] ="0.00";
        $this->staffRD[$i] ="0.00";
        }
    }
    if($this->fraisfinancier == null){
        for ($i=0 ; $i<$years;$i++){
            $this->fraisfinancier[$i] ="0.00";
            }
    }
    if($this->purchase == null){
        for ($i=0 ; $i<$years;$i++){
            $this->purchase[$i] ="0.00";
            }
    }

        return $this->render('profitand_loss/index.html.twig',['business'=>$businessSession
        ,'totalventes'=> $this->totalCA , 'achat'=> $this->purchase, 'staff' => $this->staffPro,  'staffAdm' => $this->staffAdm ,'staffCom' => $this->staffCom ,'staffRD'=> $this->staffRD,
        'generalexpense'=> $this->fraisPro, 'generalexpenseAdm' => $this->fraisAdm , 'generalexpenseCom' => $this->fraisCom, 'generalexpenseRD' => $this->fraisRD ,  
        'amortissement' => $this->amortissementPro, 'amortissementAdm' => $this->amortissementAdm , 'amortissementCom' => $this->amortissementCom , 'amortissementRD' => $this->amortissementRD
        ,'fraisfinancier' => $this->fraisfinancier,
        
        ]);

    }
 /**
  * @Route("-year-{id}", name="profitandlossdetail")
  */
  public function detail($id,Request $request ,ProductRepository $productRepository ,SalesRepository $SalesRepository,SalesdetailledRepository $SalesdetailledRepository,GeneralexpensesRepository $generalrep,LoansRepository $loansrepository){
    $businessSession =$this->container->get('session')->get('business');
    
    $this->index($request,$productRepository,$SalesRepository,$SalesdetailledRepository,$generalrep,$loansrepository);
    $response = $this->forward('App\Controller\GeneralexpensesController::detail', [
        'id'  => $id,
        'GeneralexpensesRepository'  => $generalrep,
            
    ]);
  
    $generalexpenselistPro = GeneralexpensesController::getlistPro();
    dump($this->depreciationlistPro[0]);die();
    return $this->render('profitand_loss/detail.html.twig',['id'=>$id,'business' => $businessSession , 'totalventes' => $this->totalCA[$id] , 'SumfinalCAperMouth' => $this->SumfinalCAperMouth[$id]  
    ,'achat' => $this->purchase[$id] ,'achatdetaill' => $this->purchasestatic[$id] , 'staff' => $this->staffPro[$id] , 'staffAdm' => $this->staffAdm[$id] ,'staffCom' => $this->staffCom[$id] ,'staffRD'=> $this->staffRD[$id],
    'generalexpense'=> $this->fraisPro[$id] , 'generalexpenseAdm' => $this->fraisAdm[$id] , 'generalexpenseCom' => $this->fraisCom[$id] , 'generalexpenseRD' => $this->fraisRD[$id] ,
    'amortissement' => $this->amortissementPro[$id], 'amortissementAdm' => $this->amortissementAdm[$id] , 'amortissementCom' => $this->amortissementCom[$id] , 'amortissementRD' => $this->amortissementRD[$id]
    ,'fraisfinancier' => $this->fraisfinancier[$id],
    
    'generalexpenedetailPro' => $generalexpenselistPro , 
    'depreciationdetailAdm' =>  $this->depreciationlistAdm[$id] , 'depreciationdetailPro' =>  $this->depreciationlistPro[$id] , 'depreciationdetailCom' =>  $this->depreciationlistCom[$id] , 'depreciationdetailRec' =>  $this->depreciationlistRD[$id],
    ]

);
  }
}
