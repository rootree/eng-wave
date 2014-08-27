<?php

namespace Application\Navigation;

use Zend\Navigation\Navigation;

/**
 * SmartNavigation allows assigning variables to make dynamic navigation (menu, breadcrumbs...).
 * In navigation config use 'placeholders' => true, after that you can use any placeholders in labels
 * which will be replaced with provided variables.
 * Call assignVariables() in controllers to pass extra data for navigation.
 */
class DynamicNavigation extends Navigation
{
    public function assignVariables(array $vars)
    {
        /** @var \Zend\Navigation\Page\AbstractPage $page */
        foreach($this->findAllBy('placeholders', true) as $page) {
            $label = str_replace(array_keys($vars), array_values($vars), $page->getLabel());
            $page->setLabel($label);
        }
    }
}