<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * Feedback
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Feedback")
 */
class Feedback extends Entity\Feedback
{
    /**
     * @var \Application\Model\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_user", referencedColumnName="id")
     * })
     */
    private $fkUser;



    /**
     * Set fkUser
     *
     * @param \Application\Model\Entity\User $fkUser
     *
     * @return Feedback
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
