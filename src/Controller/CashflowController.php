<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
* @Route("/{_locale}/dashboard/my-business-plan/cashflow")
 */
class CashflowController extends AbstractController
{
    /**
     * @Route("/", name="cashflow")
     */
    public function index()
    {
        $businessSession =$this->container->get('session')->get('business');
        return $this->render('cashflow/index.html.twig', [
            'business' => $businessSession,
        ]);
    }
}
