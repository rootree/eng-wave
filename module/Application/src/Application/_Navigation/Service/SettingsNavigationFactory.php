<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Navigation\DynamicNavigation;

class SettingsNavigationFactory extends DefaultNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'settings';
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $pages = parent::getPages($serviceLocator);
        return new DynamicNavigation($pages);
    }
}