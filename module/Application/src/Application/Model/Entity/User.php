<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\User")
 */
class User extends Entity\User
{
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Model\Entity\PackageUser", mappedBy="fkUser")
     * @ORM\JoinColumn(name="fk_user", referencedColumnName="id")
     */
    private $packageRelationsList;


    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $packageRelationsList
     */
    public function setPackageRelationsList($packageRelationsList)
    {
        $this->packageRelationsList = $packageRelationsList;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPackageRelationsList()
    {
        return $this->packageRelationsList;
    }

    private $settingsObject;

    public function __construct()
    {
        $this->packageRelationsList = new ArrayCollection();
    }

    /**
     * Set settings
     *
     * @param \Application\Model\Entity\UserSettings $settingsEntity
     *
     * @return User
     */
    public function setSettings($settingsEntity)
    {
        if ($settingsEntity instanceof \Application\Model\Entity\UserSettings) {
            $this->settings = $settingsEntity->encode();
        }
        return $this;
    }

    /**
     * Get settings
     *
     * @return \Application\Model\Entity\UserSettings
     */
    public function getSettings()
    {
        if (empty($this->settingsObject) && !empty($this->settings)) {
            $this->settingsObject = new \Application\Model\Entity\UserSettings($this->settings);
        }
        return $this->settingsObject;
    }

}
