<?php

namespace Application\Model\Entity\StrategyItemSettings;

use Zend\Json\Json;

class Silent extends AbstractSettings
{

    const TYPE_SOURCE  = 1;
    const TYPE_SOURCE_SIMPLE  = 4;
    const TYPE_TARGET  = 2;
    const TYPE_TARGET_SIMPLE  = 5;
    const TYPE_DEFINED = 3;

    static public $allowTypes = [
        Silent::TYPE_SOURCE,
        Silent::TYPE_SOURCE_SIMPLE,
        Silent::TYPE_DEFINED,
        Silent::TYPE_TARGET,
        Silent::TYPE_TARGET_SIMPLE,
    ];

    /**
     * @var int
     */
    public $type;

    /**
     * @var int
     */
    public $seconds = 0;

    /**
     * @param int $pauseSeconds
     */
    public function setSeconds($pauseSeconds)
    {
        $this->seconds = intval($pauseSeconds);
    }

    /**
     * @return int
     */
    public function getSeconds()
    {
        return $this->seconds;
    }

    /**
     * @param int $pauseType
     */
    public function setType($pauseType)
    {
        $this->type = intval($pauseType);
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isValidate()
    {
        if (!in_array($this->getType(), self::$allowTypes)) {
            return false;
        }
        $sec = $this->getSeconds();
        if ($this->getType() == self::TYPE_DEFINED && empty($sec)) {
            return false;
        }
        return true;
    }
}
