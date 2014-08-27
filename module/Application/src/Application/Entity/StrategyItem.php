<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StrategyItem
 *
 * @ORM\Table(name="strategy_item", indexes={@ORM\Index(name="strayegy_1", columns={"fk_strategy"}), @ORM\Index(name="user_2", columns={"fk_user"})})
 * @ORM\MappedSuperclass
 */
abstract class StrategyItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="settings", type="string", length=1000, nullable=false)
     */
    private $settings = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $sort;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return StrategyItem
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set settings
     *
     * @param string $settings
     *
     * @return StrategyItem
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get settings
     *
     * @return string
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return StrategyItem
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }
}
