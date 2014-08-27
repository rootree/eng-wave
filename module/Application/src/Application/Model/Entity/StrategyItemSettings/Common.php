<?php

namespace Application\Model\Entity\StrategyItemSettings;

use Zend\Json\Json;

class Common extends AbstractSettings
{
    /**
     * @return bool
     */
    public function isValidate()
    {
        return true;
    }
}
