<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Doctrine\DBAL\Types\Type;
use DoctrineModule\Cache\ZendStorageCache;
use Zend\Validator\AbstractValidator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->registerExceptionStrategy($e);

        /**
         * Overriding default boolean type
         * @see \Application\Service\Doctrine\DBAL\Types\BooleanType
         */
        // Type::overrideType(Type::BOOLEAN, 'Application\Service\Doctrine\DBAL\Types\BooleanType');

        // setting default translator for validators
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        AbstractValidator::setDefaultTranslator($translator);

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Register monolog error handler,
     * attach logging to Zend MVC error events.
     *
     * @param MvcEvent $event
     */
    private function registerExceptionStrategy(MvcEvent $event)
    {

    }
/*
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'doctrine.cache.filesystem' => function(ServiceManager $sm) {
                    return new ZendStorageCache($sm->get('cache.filesystem'));
                }
            ]
        ];
    }
*/
    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }
}
