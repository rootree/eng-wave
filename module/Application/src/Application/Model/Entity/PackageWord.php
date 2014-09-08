<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * PackageWord
 *
 * @ORM\Table(name="package_word")
 * @ORM\Entity
 */
class PackageWord extends Entity\PackageWord
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
     * @var \Application\Model\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_language_source", referencedColumnName="id")
     * })
     */
    private $fkLanguageSource;

    /**
     * @var \Application\Model\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_language_target", referencedColumnName="id")
     * })
     */
    private $fkLanguageTarget;

    /**
     * Set fkPackage
     *
     * @param \Application\Model\Entity\Package $fkPackage
     *
     * @return PackageWord
     */
    public function setFkPackage(\Application\Model\Entity\Package $fkPackage = null)
    {
        $this->fkPackage = $fkPackage;

        return $this;
    }

    /**
     * Get fkPackage
     *
     * @return \Application\Model\Entity\Package
     */
    public function getFkPackage()
    {
        return $this->fkPackage;
    }

    /**
     * Set fkLanguageSource
     *
     * @param \Application\Model\Entity\Language $fkLanguageSource
     *
     * @return PackageWord
     */
    public function setFkLanguageSource(\Application\Model\Entity\Language $fkLanguageSource = null)
    {
        $this->fkLanguageSource = $fkLanguageSource;

        return $this;
    }

    /**
     * Get fkLanguageSource
     *
     * @return \Application\Model\Entity\Language
     */
    public function getFkLanguageSource()
    {
        return $this->fkLanguageSource;
    }

    /**
     * Set fkLanguageTarget
     *
     * @param \Application\Model\Entity\Language $fkLanguageTarget
     *
     * @return PackageWord
     */
    public function setFkLanguageTarget(\Application\Model\Entity\Language $fkLanguageTarget = null)
    {
        $this->fkLanguageTarget = $fkLanguageTarget;

        return $this;
    }

    /**
     * Get fkLanguageTarget
     *
     * @return \Application\Model\Entity\Language
     */
    public function getFkLanguageTarget()
    {
        return $this->fkLanguageTarget;
    }
}
