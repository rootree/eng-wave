<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

use Application\Model\Entity\StrategyItemSettings\Factory as FactorySettings;
use Application\Model\Entity\StrategyItemSettings\AbstractSettings as AbstractSettings;

/**
 * StrategyItem
 *
 * @ORM\Table(name="strategy_item")
 * @ORM\Entity
 */
class StrategyItem extends Entity\StrategyItem
{

    const TYPE_SOURCE  = 1;
    const TYPE_SILENCE = 2;
    const TYPE_TARGET  = 3;

    static public $allowTypes = [
        StrategyItem::TYPE_SOURCE,
        StrategyItem::TYPE_SILENCE,
        StrategyItem::TYPE_TARGET,
    ];

    private $settingsObject;

    /**
     * @var \Application\Entity\Strategy
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Strategy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_strategy", referencedColumnName="id")
     * })
     */
    private $fkStrategy;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_user", referencedColumnName="id")
     * })
     */
    private $fkUser;


    /**
     * Set settings
     *
     * @param AbstractSettings $settingsEntity
     *
     * @return User
     */
    public function setSettings($settingsEntity)
    {
        parent::setSettings($settingsEntity->encode());
        return $this;
    }

    /**
     * Get settings
     *
     * @return \Application\Model\Entity\UserSettings
     */
    public function getSettings()
    {
        if (empty($this->settingsObject)) {
            $this->settingsObject = FactorySettings::get($this->getType(), parent::getSettings());
        }
        return $this->settingsObject;
    }


    /**
     * Set fkStrategy
     *
     * @param \Application\Model\Entity\Strategy $fkStrategy
     *
     * @return StrategyItem
     */
    public function setFkStrategy(\Application\Model\Entity\Strategy $fkStrategy = null)
    {
        $this->fkStrategy = $fkStrategy;

        return $this;
    }

    /**
     * Get fkStrategy
     *
     * @return \Application\Model\Entity\Strategy
     */
    public function getFkStrategy()
    {
        return $this->fkStrategy;
    }

    /**
     * Set fkUser
     *
     * @param \Application\Model\Entity\User $fkUser
     *
     * @return StrategyItem
     */
    public function setFkUser(\Application\Model\Entity\User $fkUser = null)
    {
        $this->fkUser = $fkUser;

        return $this;
    }

    /**
     * Get fkUser
     *
     * @return \Application\Model\Entity\User
     */
    public function getFkUser()
    {
        return $this->fkUser;
    }
}
