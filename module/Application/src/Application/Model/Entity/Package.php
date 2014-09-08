<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Package
 *
 * @ORM\Table(name="package")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Package")
 */
class Package extends Entity\Package
{

    public function __construct()
    {
        $this->userRelationsList = new ArrayCollection();
    }

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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Model\Entity\PackageUser", mappedBy="fkPackage")
     * @ORM\JoinColumn(name="fk_package", referencedColumnName="id")
     */
    private $userRelationsList;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Model\Entity\PackageWord", mappedBy="fkPackage")
     * @ORM\JoinColumn(name="fk_package", referencedColumnName="id")
     */
    private $wordsList;

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $wordsList
     */
    public function setWordsList($wordsList)
    {
        $this->wordsList = $wordsList;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWordsList()
    {
        return $this->wordsList;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $userRelationList
     */
    public function setUserRelationsList($userRelationList)
    {
        $this->userRelationsList = $userRelationList;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUserRelationsList()
    {
        return $this->userRelationsList;
    }

    /**
     * Set fkLanguageSource
     *
     * @param \Application\Model\Entity\Language $fkLanguageSource
     *
     * @return Package
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
     * @return Package
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

    /**
     * Add userRelationsList
     *
     * @param \Application\Model\Entity\PackageUser $userRelationsList
     *
     * @return Package
     */
    public function addUserRelationsList(\Application\Model\Entity\PackageUser $userRelationsList)
    {
        $this->userRelationsList[] = $userRelationsList;

        return $this;
    }

    /**
     * Remove userRelationsList
     *
     * @param \Application\Model\Entity\PackageUser $userRelationsList
     */
    public function removeUserRelationsList(\Application\Model\Entity\PackageUser $userRelationsList)
    {
        $this->userRelationsList->removeElement($userRelationsList);
    }

    /**
     * Add wordsList
     *
     * @param \Application\Model\Entity\PackageWord $wordsList
     *
     * @return Package
     */
    public function addWordsList(\Application\Model\Entity\PackageWord $wordsList)
    {
        $this->wordsList[] = $wordsList;

        return $this;
    }

    /**
     * Remove wordsList
     *
     * @param \Application\Model\Entity\PackageWord $wordsList
     */
    public function removeWordsList(\Application\Model\Entity\PackageWord $wordsList)
    {
        $this->wordsList->removeElement($wordsList);
    }
}
