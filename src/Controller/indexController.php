<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
 
class indexController extends AbstractController
{

     
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request){
      
        return $this->render("index.html.twig");
      //  return $this->render('index.html.twig');
    }
    /**
     * @Route("/{_locale}",name="{_locale}")
     */
    public function locale(Request $request){
        $locale=$request->getLocale();
      
        return $this->render($locale.".html.twig");
    }
    
      /**
       * @Route("{_locale}/select_langue/{langue}",name="select_lang")
       */
      public function select_langue($langue=null){
        if($langue != null)
        {
            $this->container->get('request')->setLocale($langue);
        }
     
        $url = $this->container->get('request')->headers->get('referer');
        if(empty($url)) {
            $url = $this->container->get('router')->generate('index');
        }
        return new RedirectResponse($url);
      }
   
  
     /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(){
        return $this->render('dashboard.html.twig');
    }
     /**
     * @Route("/admin", name="indexadmin")
     */
    public function IndexAdminAction(){
            return $this->render('admin/index_admin.html.twig');
        }
        /**
         * @Route("/admin/dashboard", name="admin")
         */
        public function AdminAction(){
            return $this->render('admin/admin_dashboard.html.twig');
        }
   

    
}