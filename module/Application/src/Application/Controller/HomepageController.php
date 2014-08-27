<?php

namespace Application\Controller;

use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\View\Model\JsonModel;

class HomepageController extends AbstractActionController
{
    public function indexAction()
    {
        $this->initGlobalSettings();
        $this->initUserSettings();


    }
}