<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
* @Route("/{_locale}/dashboard/my-business-plan/purchase")
 */
class PurchaseController extends AbstractController
{
    /**
     * @Route("/", name="purchase")
     */
    public function index()
    {
        return $this->render('purchase/index.html.twig', [
            'controller_name' => 'PurchaseController',
        ]);
    }
}
