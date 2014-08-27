<?php

namespace Application\Controller;

use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\View\Model\JsonModel;

class CliController extends AbstractCliController
{
    public function wordsSpeakerAction()
    {
        /** @var \Application\Service\Speaker $speakerService  */
        $speakerService = $this->getServiceLocator()->get('Application\Service\Speaker');

        $speakerService->getSpeaksForProceed();
    }

    public function generateStrategiesAction()
    {
        /** @var \Application\Service\Download $downloadService  */
        $downloadService = $this->getServiceLocator()->get('Application\Service\Download');

        $downloadService->proceedDownloads();
    }
}