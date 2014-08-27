<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;
use Zend\Json\Json;

/**
 * Downloads
 *
 * @ORM\Table(name="download")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Download")
 */
class Download extends Entity\Download
{
    const STATUS_INITIAL     = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_READY       = 2;
    const STATUS_CANCELED    = 3;

    /**
     * @var string
     *
     * @ORM\Column(name="legacy", type="string", length=1000, nullable=false)
     */
    private $legacy = '';

    /**
     * @var \Application\Model\Entity\WordsGroup
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\WordsGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_words_group", referencedColumnName="id")
     * })
     */
    private $fkWordsGroup;

    /**
     * @var \Application\Model\Entity\Strategy
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Strategy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_strategy", referencedColumnName="id")
     * })
     */
    private $fkStrategy;

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
     * Set fkWordsGroup
     *
     * @param \Application\Model\Entity\WordsGroup $fkWordsGroup
     *
     * @return Download
     */
    public function setFkWordsGroup(\Application\Model\Entity\WordsGroup $fkWordsGroup = null)
    {
        $this->fkWordsGroup = $fkWordsGroup;

        return $this;
    }

    /**
     * Get fkWordsGroup
     *
     * @return \Application\Model\Entity\WordsGroup
     */
    public function getFkWordsGroup()
    {
        return $this->fkWordsGroup;
    }

    /**
     * Set fkStrategy
     *
     * @param \Application\Model\Entity\Strategy $fkStrategy
     *
     * @return Download
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
     * @return Download
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
     * Set legacy
     *
     * @param string $legacy
     *
     * @return Download
     */
    public function setLegacy($legacy)
    {
        $this->legacy = Json::encode($legacy);

        return $this;
    }

    /**
     * Get legacy
     *
     * @return string
     */
    public function getLegacy()
    {
        return Json::decode($this->legacy);
    }
}
