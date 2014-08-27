<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Speaker
 *
 * @ORM\Table(name="speaker")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Speaker")
 */
class Speaker extends Entity\Speaker
{

    const STATUS_FRESH       = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_READY       = 2;

    /**
     * @var \Application\Model\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_language", referencedColumnName="id")
     * })
     */
    private $fkLanguage;

    /**
     * Set fkLanguage
     *
     * @param \Application\Model\Entity\Language $fkLanguage
     *
     * @return Speaker
     */
    public function setFkLanguage(\Application\Model\Entity\Language $fkLanguage = null)
    {
        $this->fkLanguage = $fkLanguage;

        return $this;
    }

    /**
     * Get fkLanguage
     *
     * @return \Application\Model\Entity\Language
     */
    public function getFkLanguage()
    {
        return $this->fkLanguage;
    }
}
