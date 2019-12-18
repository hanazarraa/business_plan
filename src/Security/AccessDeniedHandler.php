<?php

namespace App\Security;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;


class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $security;
    private $router;

    public function __construct(Security $security, UrlGeneratorInterface  $router)
    {
        $this->security = $security;
        $this->router = $router;
    }


    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
      
        if ($this->security->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->router->generate('dashboard'));
        }
        else if($this->security->isGranted('ROLE_ADMIN')){
            return new RedirectResponse($this->router->generate('admin'));
        }
        return new RedirectResponse($this->router->generate('app_login'));


    }
}