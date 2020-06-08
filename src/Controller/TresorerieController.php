<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
* @Route("/{_locale}/dashboard/my-business-plan/tresorerie")
 */
class TresorerieController extends AbstractController
{
    /**
     * @Route("/", name="tresorerie")
     */
    public function index()
    {
        $businessSession =$this->container->get('session')->get('business');
        return $this->render('tresorerie/index.html.twig', [
            'business' => $businessSession,
        ]);
    }
}
