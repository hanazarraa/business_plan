<?php
namespace App\Services;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_SmtpTransportInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\RawMessage;
class MailerService{
  protected $mailer;
  protected $templating;
  protected $transport; // <- Add transport to your service

  public function __construct(\Swift_Mailer $mailer , \Twig_Environment $templating )
  {
      $this->templating=$templating;
      $this->mailer    = $mailer;
  }

  public function sendToken($token,$email,$username,$view) // <- change the method like this
  {

    $transport = new \Swift_SmtpTransport('smtp.gmail.com', 465,'ssl');
       $transport->setUsername('hanazarraa53@gmail.com')
                  ->setPassword('wuwbsawbfekxvpau');

    $this->mailer =new \Swift_Mailer($transport);
    $message = new \Swift_Message('Activation compte');
           $url="https://127.0.0.1:8000/account/confirm/".$token;
           $url1="https://127.0.0.1:8000/reset-password/".$token;
         $message->setFrom(array('hanazarraa53@gmail.com' => 'MyBusinessplan'))
         ->setContentType('text/html')
         ->setTo($email)
         ->setBody($this->templating->render($view,array('token'=>$token,'email'=>$email,'username'=>$username,'url'=>$url,"url1"=>$url1)));
    $this->mailer->send($message);
  // Create a message
 
    
  }
}