<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
* @Route("/{_locale}/dashboard/my-business-plan/IS")
 */
class ISController extends AbstractController
{
    /**
     * @Route("/", name="IS")
     */
    public function index()
    {
        $businessSession =$this->container->get('session')->get('business');
        return $this->render('is/index.html.twig', [
            'business' => $businessSession,
        ]);
    }
}
