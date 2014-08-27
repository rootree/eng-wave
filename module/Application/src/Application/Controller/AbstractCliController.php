<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\Json\Json;
use Zend\Stdlib\ArrayUtils;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Application\Model\Entity\Language as LanguageEntity;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\Download as DownloadEntity;
use Application\Model\Entity\Strategy as StrategyEntity;
use Application\Model\Entity\StrategyItem as StrategyItemEntity;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Exception\DomainException;
use Api\Model\Exception as ApiException;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;

abstract class AbstractCliController extends ZendAbstractActionController
{

    /**
     * @inheritDoc
     */
    public function onDispatch(MvcEvent $event)
    {
        try {
            $actionResponse = parent::onDispatch($event);
        } catch (\Exception $e) {
            $sm = $event->getApplication()->getServiceManager();
            $service = $sm->get('ApplicationServiceErrorHandling');
            $service->logException($e);
            $actionResponse = $e->getMessage();

            echo $e . PHP_EOL;
        }
        return $actionResponse;
    }
}

