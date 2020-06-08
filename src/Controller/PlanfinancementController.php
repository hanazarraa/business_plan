<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
* @Route("/{_locale}/dashboard/my-business-plan/planfinancement")
 */
class PlanfinancementController extends AbstractController
{
    /**
     * @Route("/", name="planfinancement")
     */
    public function index()
    {
        $businessSession =$this->container->get('session')->get('business');
        return $this->render('planfinancement/index.html.twig', [
            'business' => $businessSession,
        ]);
    }
}
