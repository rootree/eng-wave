<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;


/**
 * PackageUser
 *
 * @ORM\Table(name="package__user")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\PackageUser")
 */
class PackageUser extends Entity\PackageUser
{
    /**
     * @var \Application\Model\Entity\Package
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Package")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_package", referencedColumnName="id")
     * })
     */
    private $fkPackage;

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
     * @param \Application\Model\Entity\Package $fkPackage
     */
    public function setFkPackage($fkPackage)
    {
        $this->fkPackage = $fkPackage;
    }

    /**
     * @return \Application\Model\Entity\Package
     */
    public function getFkPackage()
    {
        return $this->fkPackage;
    }

    /**
     * @param \Application\Model\Entity\User $fkUser
     */
    public function setFkUser($fkUser)
    {
        $this->fkUser = $fkUser;
    }

    /**
     * @return \Application\Model\Entity\User
     */
    public function getFkUser()
    {
        return $this->fkUser;
    }
}
