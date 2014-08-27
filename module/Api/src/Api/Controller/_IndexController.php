<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->setCSRF('csrf_token');
/*
        $authenticationService = $this->serviceLocator()->get('Zend\Authentication\AuthenticationService');
        $loggedUser            = $authenticationService->getIdentity();

 */
        $user = $this->identity();
        //var_export($user);
        if (!$user) {
            // if not log in, redirect to login page
            //return $this->redirect()->toUrl('/login');
        }
/*        $authService = $this->serviceLocator->get('auth_service');
        if (!$authService->hasIdentity()) {
            // if not log in, redirect to login page
            return $this->redirect()->toUrl('/login');
        }*/

        return new ViewModel();
    }
}
