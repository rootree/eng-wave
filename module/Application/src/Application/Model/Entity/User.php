<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\User")
 */
class User extends Entity\User
{

    private $settingsObject;

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
        if (empty($this->settingsObject)) {
            $this->settingsObject = new \Application\Model\Entity\UserSettings($this->settings);
        }
        return $this->settingsObject;
    }

}
