<?php

namespace Application\Model\Entity\StrategyItemSettings;

use Zend\Json\Json;

use Application\Model\Entity\StrategyItemSettings\Silent as SilentSettings;
use Application\Model\Entity\StrategyItemSettings\AbstractSettings as AbstractSettings;
use Application\Model\Entity\StrategyItem as StrategyItemEntity;

class Factory
{
    /**
     * @param $type
     * @param $values
     * @param bool $isJSON
     *
     * @return AbstractSettings
     */
    static public function get($type, $values, $isJSON = false) {

        switch ($type) {
            case StrategyItemEntity::TYPE_SILENCE:
                return new SilentSettings($values, $isJSON);
        }

        return new Common($values, $isJSON);
    }
}
