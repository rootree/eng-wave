<?php

use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Cache\ZendStorageCache;
use \Application\Service\ErrorHandling as ErrorHandlingService;
use \Zend\Log\Logger as ZendLogLogger;
use \Application\Service\Speaker as SpeakerEngine;
use \Zend\Log\Writer\Stream as LogWriterStream;

return array(
    'factories' => array(
        'Application\Service\Word' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            $speakerService = $sl->get('Application\Service\Speaker');
            return new \Application\Service\Word($em, $speakerService);
        },
        'Application\Service\Strategy' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new \Application\Service\Strategy($em);
        },
        'Application\Service\WordsGroup' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new \Application\Service\WordsGroup($em);
        },
        'Application\Service\Language' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new \Application\Service\Language($em);
        },
        'Application\Service\Feedback' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            return new \Application\Service\Feedback($em);
        },
        'Application\Service\Package' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            $wordService = $sl->get('Application\Service\Word');
            return new \Application\Service\Package($em, $wordService);
        },
        'Application\Service\User' => function (ServiceLocatorInterface $sl) {

            $em = $sl->get('Doctrine\ORM\EntityManager');

            $wordsGroupService = $sl->get('Application\Service\WordsGroup');
            $strategyService   = $sl->get('Application\Service\Strategy');
            $packageService   = $sl->get('Application\Service\Package');

            return new \Application\Service\User($em, $wordsGroupService, $strategyService, $packageService);
        },
        'Application\Service\Store' => function (ServiceLocatorInterface $sl) {
            $storeConfig = $sl->get('config');
            return new \Application\Service\Store($storeConfig['store']);
        },
        'Application\Service\Download' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');

            $speakerService = $sl->get('Application\Service\Speaker');
            $storeService   = $sl->get('Application\Service\Store');
            $emailService   = $sl->get('Application\Service\Email');

            return new \Application\Service\Download($em, $speakerService, $storeService, $emailService);
        },
        'Application\Service\Speaker' => function (ServiceLocatorInterface $sl) {
            $em = $sl->get('Doctrine\ORM\EntityManager');
            $storeService = $sl->get('Application\Service\Store');
            $zendLogService = $sl->get('ZendLog');
            return new \Application\Service\Speaker($em, $storeService, $zendLogService);
        },
        'Application\Service\Email' => function (ServiceLocatorInterface $sl) {
            $appConfig = $sl->get('config');
            return new \Application\Service\Email($appConfig['store']);
        },
        'doctrine.cache.filesystem' => function(ServiceManager $sm) {
            return new ZendStorageCache($sm->get('cache.filesystem'));
        },

        'Zend\Authentication\AuthenticationService' => function($serviceManager) {
            // If you are using DoctrineORMModule:
            return $serviceManager->get('doctrine.authenticationservice.orm_default');

            // If you are using DoctrineODMModule:
            return $serviceManager->get('doctrine.authenticationservice.odm_default');
        },
        'ApplicationServiceErrorHandling' =>  function($sm) {
            $logger = $sm->get('ZendLog');
            $service = new ErrorHandlingService($logger);
            return $service;
        },
        'ZendLog' => function ($sm) {
            $config = $sm->get('config');
            $filename = $config['logs']['filename'];
            $log = new ZendLogLogger();
            $writer = new LogWriterStream($config['logs']['path'] . $filename);
            $log->addWriter($writer);
            return $log;
        },
    )
);