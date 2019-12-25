<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
 
class HomeController extends AbstractController
{
 
     /**
     * @Route("/{_locale}/dashboard", name="dashboard")
     */
    public function dashboardAction(){
        return $this->render('dashboard.html.twig');
    }
     /**
     * @Route("/{_locale}/admin", name="indexadmin")
     */
    public function IndexAdminAction(){
            return $this->render('admin/index_admin.html.twig');
        }
        /**
         * @Route("/{_locale}/admin/dashboard", name="admin")
         */
        public function AdminAction(){
            return $this->render('admin/admin_dashboard.html.twig');
        }
   

    
}