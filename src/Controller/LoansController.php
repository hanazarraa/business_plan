<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Loans;
use App\Form\LoansFormType;
use App\Repository\LoansRepository;
/**
* @Route("/{_locale}/dashboard/my-business-plan/loans")
 */
class LoansController extends AbstractController
{
    /**
     * @Route("/", name="loans")
     */
    public function index(LoansRepository $loansrepository)
    {
        $businessSession =$this->container->get('session')->get('business');
        $loans= $loansrepository->findBybusinessplan($businessSession);
        
        if($loans == null){
        return $this->render('loans/index.html.twig', [
            'business' => $businessSession,
        ]);}
        else{
            return $this->render('loans/loans.html.twig', [
                'business' => $businessSession,'loans' => $loans
            ]);
        }
    }
     /**
     * @Route("/create-loans", name="createloans")
     */
    public function create(Request $request){
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $loans = new Loans();
        $loans->setBusinessplan($businessSession);
        $loans->setCode("".strtoupper(bin2hex(openssl_random_pseudo_bytes(32))));
        $form = $this->createForm(LoansFormType::class, $loans);
        $form->handleRequest($request);
        
        
        if($form->isSubmitted() && $form->isValid()){
            $loans = $form->getData();
           
            $entityManager->merge($loans);
            $entityManager->flush();
            return $this->redirectToRoute('loans');

        }
        return $this->render('loans/create.html.twig' , ['business' =>$businessSession  ,'form' => $form->createView()]);
    }
     /**
     * @Route("/edit/{code}", name="editloans")
     */
    public function edit($code,Request $request){
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        
        $loans = $entityManager->getRepository(Loans::class)->findByCode($code);
        $form = $this->createForm(LoansFormType::class, $loans[0]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $loans = $form->getData();
            $entityManager->flush();
            return $this->redirectToRoute('loans');
        }

        return $this->render('loans/edit.html.twig' , ['business' =>$businessSession ,
        'form' => $form->createView()]);
    }
     /**
     * @Route("/delete/{code}", name="deleteloans")
     */
    public function delete($code,Request $request){
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $loans = $entityManager->getRepository(Loans::class)->findByCode($code);
        $entityManager->remove($loans[0]);
        $entityManager->flush();
        return $this->redirectToRoute('loans');
    }
    private $remb ;
    /**
     * @Route("/show/{code}", name="showloans")
     */
    public function detail($code,Request $request){
        $businessSession =$this->container->get('session')->get('business');
        $entityManager = $this->getDoctrine()->getManager();
        $loans = $entityManager->getRepository(Loans::class)->findByCode($code);
        $years = $businessSession->getNumberofyears();
        //---------------- variable de l'emprunt----------------------------// 
        $dateemprunt = $loans[0]->getLoandate();
        $startempruntM = $dateemprunt->format('m');
        $startempruntY = $dateemprunt->format('Y');
        $datefirstpayment = $loans[0]->getFirstpaymentdate();
        $startposition = $datefirstpayment->format('m') ;
        $startpositionY = $datefirstpayment->format('Y') ;
        $amount = $loans[0]->getAmount();
        $taux = $loans[0]->getTaux();
        $duration = $loans[0]->getDuration();
        $diffirence =  date_diff($datefirstpayment,$dateemprunt) ;
        $diff = (int) ($diffirence->days / 30.417);
        $listchange= false;
        //------------------variable de business plan ---------------------//
        $annedebut  = $businessSession->getStartyear();
        $moisdebut  =   $businessSession->getStartmonth();
        $moisdebutInteger = $this->transformMounth($moisdebut);
      
        //-----------------------Fin------------------------------//
        //------------------------echeance-----------------------------------//
        $echancetoppart = $amount * (($taux / 100)/12); //partie top de la formule de subdivision  (Formule Emprunt)
        $echancedownpart = (1- pow((1+ ($taux/100/12)),-$duration*12+$diff)); // partie down de la formule de subdivision 
        $echance = round($echancetoppart / $echancedownpart,2);
        
        //--------------------------Fin echance-----------------------------// 
        //initialiser les listes pour le capital rembourser & les frais financier
        for($x=0 ;$x<12*$years; $x++ ){
        $capitalrembourse[$x] = "0.00";
        $fraisfinanciers[$x] = "0.00";
        $capitalrestantdu[$x] = "0.00"  ;
        }
        for($i=0;$i<$years ; $i++){
            $remboursmentsynchroniserSum[$i] = "0.00";
            $fraissynchroniserSum[$i] = "0.00";
            $restantsynchroniserSum[$i] = "0.00";
        for($x=0;$x<12;$x++){
            
            $fraissynchroniser[$i][$x] =  "0.00"  ;
            $restantsynchroniser[$i][$x] = "0.00" ;
           
        }}
        for($i=0;$i<$years+1 ; $i++){
            for($x=-1;$x<12;$x++){
                $remboursmentsynchroniser[$i][$x] = "0.00" ;
        }}
        //-----------------------------fin d'initialisation -------------------//
       //----------------------------debut de calcul de remboursement si mensualite egale 12--------------------------//
       $diffrenceBPandLoansYear=$startempruntY -  $annedebut   ;
       $diffrenceechanceEmpruntandEmprunt = $startpositionY - $startempruntY ;
       $capitalrestant = $amount ;
       $compteurdepart = $startposition;//date debut echeance 
       //$compteurfin = $startempruntM  +($duration * 12);  // durée a partie de date de debut l'emprunt 
      
       for($i=$startposition - 1 + ($diffrenceechanceEmpruntandEmprunt * 12) ;$i<$duration*12 + ($startempruntM - 1);$i++ ){
        $capitalrembourse[$i] = $echance - round(($capitalrestant * (($taux/100)/12)),2);
        $fraisfinanciers[$i]  = round(($capitalrestant * (($taux/100)/12)),2);
        $capitalrestantdu[$i] = $capitalrestant;
        $capitalrestant -= $capitalrembourse[$i] ;
      }
     // dump( $capitalrembourse);die();
      
       //----------------------------fin de calcul-----------------------------//
       //-----------------------reformuler les listes -------------------------//
       $pos = 0 ;
       $year = 0 ;
       for($i =0; $i < $years*12; $i++){
       $finalrembrousement[$year][$pos] = $capitalrembourse[$i] ;
       $finalfrais[$year][$pos] = $fraisfinanciers[$i];
       $finalrestant[$year][$pos] = $capitalrestantdu[$i];
       $pos++;
       if($pos == 12 ){
       $pos = 0 ;
       $year ++;}}
    
       //----------------------2eme reformulation (synchroniser avec le business plan par années) ----//
       
       $ye = $diffrenceBPandLoansYear  ;
       
      // dump($finalrembrousement);die();
       for($i = 0 ; $i< $years;$i++){
           for($x = 0 ; $x < 12 ; $x++){
         $remboursmentsynchroniser[$i + $ye][$x] = $finalrembrousement[$i][$x] ;
         $remboursmentsynchroniserSum[$i + $ye] = array_sum($finalrembrousement[$i]);
         $fraissynchroniser[$i + $ye][$x] = $finalfrais[$i][$x]; 
         $fraissynchroniserSum[$i + $ye] = array_sum($finalfrais[$i]);
         $restantsynchroniser[$i + $ye][$x]  = $finalrestant[$i][$x] ;
         $restantsynchroniserSum[$i + $ye] = array_sum($finalrestant[$i]);
       }}
       
       //----------------------attacher les valeur avec le  mois correspendant ------------//
       if(!($moisdebutInteger  == $startempruntM + 1 &&  $startempruntY == $annedebut)){
           
       
            $Preparedremboursment = $this->decalage($remboursmentsynchroniser,$moisdebutInteger - 1,$years);
        
           
        }
           
    
       else {
           $listchange = true ;
           $Preparedremboursment =   $this->exception($remboursmentsynchroniser,$moisdebutInteger - 1,$years);   
           
       }
     
       $L = $this->preparelistmonth($moisdebutInteger - 1);
       $defaultlist =['Janv','Fév','Mars','Avril','Mai','Juin','Juil','Aout','Sept','Oct','Nov','Déc'] ;
       
       for($i = 0 ; $i< $years;$i++){
       foreach($defaultlist as $key=>$value){
          $remboursmentforshow[$i][$value] = $Preparedremboursment[$i][$key];
               
       }} 
      
      
      
       /* if(!($moisdebutInteger  == $startempruntM + 1 &&  $startempruntY == $annedebut)){
            for($i = 0 ; $i< $years;$i++){
                for($x = 0 ; $x < 12 ; $x++){
                  //  $remboursmentforshow[$i][$x-($moisdebutInteger-1)] =$remboursmentsynchroniser[$i][$x];
        }}
       }*/
      
       //dump($remboursmentsynchroniser,$remboursmentforshow,$L);die();
      //------------------------------fin--------------------------------------//
       //dump($remboursmentsynchroniser);die();
        return $this->render('loans/detail.html.twig' , ['business' =>$businessSession
        ,'remboursment' => $remboursmentsynchroniser,'frais' =>$fraissynchroniser , 'restant' =>  $restantsynchroniser
        ,'sumremboursment' => $remboursmentsynchroniserSum ,  'sumfrais' => $fraissynchroniserSum ,'MonthList' => $L
       ,'sumrestant' => $restantsynchroniserSum ,'remboursmentforshow' => $remboursmentforshow
        ]);
    }
    public function decalage(Array $Listadecaler , int $indice,int $years){//cette fonction permet de decaler une liste a partire la liste $L 
        for($x=0 ;$x<$years+1; $x++ ){
            for($i=0;$i< 12  ; $i++){
             $remboursment[$x][$i] = "0.00";
            }}
          
        for($i=0;$i<$years;$i++){
       for($x=0;$x<$indice;$x++){
            $remboursment[$i][$x] = $Listadecaler[$i+1][$x];
        }
       
        for($x=$indice;$x<12;$x++){
            $remboursment[$i][$x] = $Listadecaler[$i][$x];
        }
    }
    return $remboursment;
    }
    public function exception(Array $Listadecaler , int $indice,int $years){
        for($x=0 ;$x<$years+1; $x++ ){
            for($i=0;$i< 12  ; $i++){
             $remboursment[$x][$i] = "0.00";}}
        for($i=0;$i<$years;$i++){               
        for($x=0;$x<$indice;$x++){
            $remboursment[$i][$x] = $Listadecaler[$i][12-$indice];
            }
        for($x=$indice;$x<12;$x++){
         $remboursment[$i][$x] = $Listadecaler[$i][$x-1];}}        
        
 return  $remboursment ;
    }
    public function preparelistmonth(int $firstdate){ // cette fonction permet de decaler les mois  a partir de debut de mois d'un business plan
    $defaultlist =['Janv','Fév','Mars','Avril','Mai','Juin','Juil','Aout','Sept','Oct','Nov','Déc'] ;
    if($firstdate>0 && $firstdate <12){
    $indextable = $firstdate ;
    for($i = 0 ; $i<$indextable;$i++){
    $partiegauche[$i] = $defaultlist[$i];
    }
    for($i = $indextable ; $i<12;$i++){
        $partiedroite[$i] = $defaultlist[$i];
        }
    $finallist=   array_merge($partiedroite,$partiegauche);
}
    else{
        $finallist = $defaultlist;
    }
 
    return $finallist;
}


    public function transformMounth(String $input){
    if($input == 'Jan'){
     return   1;
    }
    if($input =='Feb'){
        return   2;
       }
       if($input =='March'){
        return   3;
       }
       if($input =='April'){
        return   4;
       }
       if($input =='May'){
        return   5;
       }
       if($input =='June'){
        return   6;
       }
       if($input =='July'){
        return   7;
       }
       if($input =='Aug'){
        return   8;
       }
       if($input =='Sep'){
        return   9;
       }
       if($input =='Oct'){
        return   10;
       }
       if($input =='Nov'){
        return   11;
       }
       if($input =='Dec'){
        return   12;
       }
    }
}
