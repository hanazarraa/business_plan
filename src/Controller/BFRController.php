<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
* @Route("/{_locale}/dashboard/my-business-plan/BFR")
 */
class BFRController extends AbstractController
{
    /**
     * @Route("/", name="BFR")
     */
    public function index()
    {
        $businessSession =$this->container->get('session')->get('business');
        return $this->render('bfr/index.html.twig', [
            'business' => $businessSession,
        ]);
    }
}
