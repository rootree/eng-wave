<?php

namespace Application\Model\Entity\StrategyItemSettings;

use Zend\Json\Json;

abstract class AbstractSettings
{

    function __construct($strategyItemSetting, $isJSON)
    {
        $strategyItemSettingsStd = $isJSON
            ? $strategyItemSetting
            : Json::decode($strategyItemSetting)
        ;
        foreach ($strategyItemSettingsStd as $setting => $value) {
            $methodName = sprintf('set%s', ucfirst($setting));
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }
    }

    /**
     * @return string
     */
    public function encode()
    {
        return Json::encode($this);
    }

    /**
     * @return bool
     */
    abstract public function isValidate();
}
