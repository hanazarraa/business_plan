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
                'business' => $businessSession,
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
}
