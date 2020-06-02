<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SalesRepository;
use App\Repository\SalesdetailledRepository;
use App\Repository\GeneralexpensesRepository;
use App\Repository\ProductRepository;

/**
     * @Route("/{_locale}/dashboard/my-business-plan/profitandloss")
 */
class ProfitandLossController extends AbstractController
{
    /**
     * @Route("/", name="profitandloss")
     */
    public function index(Request $request ,ProductRepository $productRepository ,SalesRepository $SalesRepository,SalesdetailledRepository $SalesdetailledRepository,GeneralexpensesRepository $generalrep)
    {
        $businessSession =$this->container->get('session')->get('business');
        $years = $businessSession->getNumberofyears();  
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
        $response = $this->forward('App\Controller\DepreciationController::index', []);
        $finalCA = SalesController::getfinalca(); 
        $purchase = PurchaseController::getlistpurchase();
        $staff = StaffController::getstaff();
        $generalexpense = GeneralexpensesController::getpurchase();
        $depreciation = DepreciationController::getdepreciation();
        for($x = 0 ; $x < $years ; $x++){
            for($i = 0 ; $i < 12 ; $i++){
              $SumfinalCAperMouth[$x][$i] =  0 ;}} 
        foreach($finalCA as $key=>$value){
            for($x = 0 ; $x < $years ; $x++){
             for($i = 0 ; $i < 12 ; $i++){
               $SumfinalCAperMouth[$x][$i] +=  $finalCA[$key][$x][$i] ;}}}
          
               for($x = 0 ; $x < $years ; $x++){
                $totalCA[$x] = array_sum($SumfinalCAperMouth[$x]);
               }
        
        return $this->render('profitand_loss/index.html.twig',['business'=>$businessSession
        ,'totalventes'=> $totalCA , 'achat'=> $purchase, 'staff' => $staff, 'generalexpense'=> $generalexpense, 
        'amortissement' => $depreciation,
        ]);
    }
}
