<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="strategy")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Strategy")
 */
class Strategy extends Entity\Strategy
{
    const TYPE_SOURCE = 1;
    const TYPE_TARGET = 2;


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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Model\Entity\StrategyItem", mappedBy="fkStrategy")
     * @ORM\JoinColumn(name="fk_strategy", referencedColumnName="id")
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $items;


    public function __construct()
    {
        $this->items = new ArrayCollection();
    }



    /**
     * Get items
     *
     * @return \Application\Model\Entity\StrategyItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set items
     *
     * @param \Application\Model\Entity\StrategyItem[] $items
     * @return WordsGroup
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Set fkUser
     *
     * @param \Application\Model\Entity\User $fkUser
     *
     * @return Strategy
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

    /**
     * Add item
     *
     * @param \Application\Model\Entity\StrategyItem $item
     *
     * @return Strategy
     */
    public function addItem(\Application\Model\Entity\StrategyItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Application\Model\Entity\StrategyItem $item
     */
    public function removeItem(\Application\Model\Entity\StrategyItem $item)
    {
        $this->items->removeElement($item);
    }
}
