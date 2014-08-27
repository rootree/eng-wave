<?php

namespace Application\Model\Entity;

use Zend\Json\Json;

class UserSettings
{
    /**
     * @var integer
     */
    public $currentGroup;

    function __construct($userSetting)
    {
        $userSettingStd = Json::decode($userSetting, Json::TYPE_ARRAY);
        if (is_array($userSettingStd)) {
            foreach ($userSettingStd as $setting => $value) {
                $methodName = sprintf('set%s', ucfirst($setting));
                if (method_exists($this, $methodName)) {
                    $this->$methodName($value);
                }
                $this->$setting = $value;
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
     * @param int $currentGroup
     */
    public function setCurrentGroup($currentGroup)
    {
        $this->currentGroup = $currentGroup;
    }

    /**
     * @return int
     */
    public function getCurrentGroup()
    {
        return $this->currentGroup;
    }
}
