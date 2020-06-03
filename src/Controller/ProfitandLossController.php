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
        $purchase = PurchaseController::getlistpurchase();
        $staff = StaffController::getstaff();
        $generalexpense = GeneralexpensesController::getpurchase();
        $generalexpenseAdmG = GeneralexpensesController::getfraisAdmG();
        $generalexpenseComG = GeneralexpensesController::getfraisComG();
        $generalexpenseRDG = GeneralexpensesController::getfraisRDG();

        $generalexpenseAdmD = GeneralexpensesController::getfraisAdmD();
        $generalexpenseComD = GeneralexpensesController::getfraisComD();
        $generalexpenseRDD = GeneralexpensesController::getfraisRDD();

        $depreciation = DepreciationController::getdepreciation();
        $depreciationAdmG = DepreciationController::getdepAdmG();
        $depreciationComG = DepreciationController::getdepComG();
        $depreciationRDG = DepreciationController::getdepRDG();

        $depreciationAdmD = DepreciationController::getdepAdmD();
        $depreciationComD = DepreciationController::getdepComD();
        $depreciationRDD = DepreciationController::getdepRDD();


        $staffAdm = StaffController::getstaffAdm();
        $staffCom = StaffController::getstaffCom();
        $staffRD = StaffController::getstaffRD();

        $fraisfinancier = LoansController::getfraisfinancier();
       
       
        for($x = 0 ; $x < $years ; $x++){
            for($i = 0 ; $i < 12 ; $i++){
              $SumfinalCAperMouth[$x][$i] =  0 ;}} 
        foreach($finalCA as $key=>$value){
            for($x = 0 ; $x < $years ; $x++){
            for($i = 0 ; $i < 12 ; $i++){
               $SumfinalCAperMouth[$x][$i] +=  $finalCA[$key][$x][$i] ;}}}
          
            for($x = 0 ; $x < $years ; $x++){
                $totalCA[$x] = array_sum($SumfinalCAperMouth[$x]);}
        //-------------------------generalexpenses        
        $pos = 0 ;
        for ($i=0 ; $i<$years;$i++){
        if($pos < $rangeofdetail){
            $fraisAdm[$i] = $generalexpenseAdmD[$i];
            $fraisCom[$i] = $generalexpenseComD[$i];
            $fraisRD[$i] = $generalexpenseRDD[$i];
            $pos++ ;
        }
        
        else if ($pos >= $rangeofdetail ){
            $fraisAdm[$i] = $generalexpenseAdmG[$i - $rangeofdetail];
            $fraisCom[$i] = $generalexpenseComG[$i - $rangeofdetail];
            $fraisRD[$i] = $generalexpenseRDG[$i - $rangeofdetail];
            $pos++ ;
        }}
        //-------------------------depreciation-------------------
        for($i=0 ; $i<$years;$i++){
            $amortissementAdm[$i] ="0.00";
            $amortissementCom[$i] ="0.00";
            $amortissementRD[$i] ="0.00";
        }
        
        $pos = 0 ;
        for ($i=0 ; $i<$years;$i++){
    
        $amortissementAdm[$i] += $depreciationAdmD[$i] + $depreciationAdmG[$i];
        $amortissementCom[$i] += $depreciationComD[$i] + $depreciationComG[$i];
        $amortissementRD[$i] += $depreciationRDD[$i] + $depreciationRDG[$i];      
    }
    
        
        return $this->render('profitand_loss/index.html.twig',['business'=>$businessSession
        ,'totalventes'=> $totalCA , 'achat'=> $purchase, 'staff' => $staff,  'staffAdm' => $staffAdm ,'staffCom' => $staffCom ,'staffRD'=> $staffRD,
        'generalexpense'=> $generalexpense, 'generalexpenseAdm' => $fraisAdm , 'generalexpenseCom' => $fraisCom, 'generalexpenseRD' => $fraisRD ,  
        'amortissement' => $depreciation, 'amortissementAdm' => $amortissementAdm , 'amortissementCom' => $amortissementCom , 'amortissementRD' => $amortissementRD
        ,'fraisfinancier' => $fraisfinancier,
        
        ]);

    }
 /**
  * @Route("-year-{id}", name="profitandlossdetail")
  */
  public function detail($id){
    $businessSession =$this->container->get('session')->get('business');
    return $this->render('profitand_loss/detail.html.twig',['business' => $businessSession]);
  }
}
