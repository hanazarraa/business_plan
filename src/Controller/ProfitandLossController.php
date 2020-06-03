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
/**
     * @Route("/{_locale}/dashboard/my-business-plan/profitandloss")
 */
class ProfitandLossController extends AbstractController
{
     private $totalCA ;
     private $purchase ;
     private $staff;
     private $staffAdm ;
     private $staffCom ;
     private $staffRD;
     private $generalexpense;
     private $generalexpenseAdmG;
     private $generalexpenseComG;
     private $generalexpenseRDG;
     private $generalexpenseAdmD ;
     private $generalexpenseComD;
     
     private $fraisAdm;
     private $fraisCom;
     private $fraisRD ;
     private $depreciation;
     private $depreciationAdmG ;
     private $depreciationComG ; 
     private $depreciationRDG;

     private $amortissementAdm;
     private  $amortissementCom;
     private $amortissementRD;
     private $fraisfinancier ;

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
        $this->staff = StaffController::getstaff();
        $this->generalexpense = GeneralexpensesController::getpurchase();
        $generalexpenseAdmG = GeneralexpensesController::getfraisAdmG();
        $generalexpenseComG = GeneralexpensesController::getfraisComG();
        $generalexpenseRDG = GeneralexpensesController::getfraisRDG();

        $generalexpenseAdmD = GeneralexpensesController::getfraisAdmD();
        $generalexpenseComD = GeneralexpensesController::getfraisComD();
        $generalexpenseRDD = GeneralexpensesController::getfraisRDD();

        $this->depreciation = DepreciationController::getdepreciation();
        $depreciationAdmG = DepreciationController::getdepAdmG();
        $depreciationComG = DepreciationController::getdepComG();
        $depreciationRDG = DepreciationController::getdepRDG();

        $depreciationAdmD = DepreciationController::getdepAdmD();
        $depreciationComD = DepreciationController::getdepComD();
        $depreciationRDD = DepreciationController::getdepRDD();


        $this->staffAdm = StaffController::getstaffAdm();
        $this->staffCom = StaffController::getstaffCom();
        $this->staffRD = StaffController::getstaffRD();

        $this->fraisfinancier = LoansController::getfraisfinancier();
       
       
        for($x = 0 ; $x < $years ; $x++){
            for($i = 0 ; $i < 12 ; $i++){
              $SumfinalCAperMouth[$x][$i] =  0 ;}} 
        foreach($finalCA as $key=>$value){
            for($x = 0 ; $x < $years ; $x++){
            for($i = 0 ; $i < 12 ; $i++){
               $SumfinalCAperMouth[$x][$i] +=  $finalCA[$key][$x][$i] ;}}}
          
            for($x = 0 ; $x < $years ; $x++){
                $this->totalCA[$x] = array_sum($SumfinalCAperMouth[$x]);}
        //-------------------------generalexpenses        
        $pos = 0 ;
        for ($i=0 ; $i<$years;$i++){
        if($pos < $rangeofdetail){
            $this->fraisAdm[$i] = $generalexpenseAdmD[$i];
            $this->fraisCom[$i] = $generalexpenseComD[$i];
            $this->fraisRD[$i] = $generalexpenseRDD[$i];
            $pos++ ;
        }
        
        else if ($pos >= $rangeofdetail ){
            $this->fraisAdm[$i] = $generalexpenseAdmG[$i - $rangeofdetail];
            $this->fraisCom[$i] = $generalexpenseComG[$i - $rangeofdetail];
            $this->fraisRD[$i] = $generalexpenseRDG[$i - $rangeofdetail];
            $pos++ ;
        }}
        //-------------------------depreciation-------------------
        for($i=0 ; $i<$years;$i++){
            $this->amortissementAdm[$i] ="0.00";
            $this->amortissementCom[$i] ="0.00";
            $this->amortissementRD[$i] ="0.00";
        }
        
        $pos = 0 ;
        for ($i=0 ; $i<$years;$i++){
    
            $this->amortissementAdm[$i] += $depreciationAdmD[$i] + $depreciationAdmG[$i];
            $this->amortissementCom[$i] += $depreciationComD[$i] + $depreciationComG[$i];
            $this->amortissementRD[$i] += $depreciationRDD[$i] + $depreciationRDG[$i];      
    }
    
        
        return $this->render('profitand_loss/index.html.twig',['business'=>$businessSession
        ,'totalventes'=> $this->totalCA , 'achat'=> $this->purchase, 'staff' => $this->staff,  'staffAdm' => $this->staffAdm ,'staffCom' => $this->staffCom ,'staffRD'=> $this->staffRD,
        'generalexpense'=> $this->generalexpense, 'generalexpenseAdm' => $this->fraisAdm , 'generalexpenseCom' => $this->fraisCom, 'generalexpenseRD' => $this->fraisRD ,  
        'amortissement' => $this->depreciation, 'amortissementAdm' => $this->amortissementAdm , 'amortissementCom' => $this->amortissementCom , 'amortissementRD' => $this->amortissementRD
        ,'fraisfinancier' => $this->fraisfinancier,
        
        ]);

    }
 /**
  * @Route("-year-{id}", name="profitandlossdetail")
  */
  public function detail($id,Request $request ,ProductRepository $productRepository ,SalesRepository $SalesRepository,SalesdetailledRepository $SalesdetailledRepository,GeneralexpensesRepository $generalrep,LoansRepository $loansrepository){
    $businessSession =$this->container->get('session')->get('business');
    $this->index($request,$productRepository,$SalesRepository,$SalesdetailledRepository,$generalrep,$loansrepository);
    dump($this->totalCA);
    return $this->render('profitand_loss/detail.html.twig',['business' => $businessSession]);
  }
}
