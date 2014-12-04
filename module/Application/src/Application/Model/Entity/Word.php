<?php

/**
./vendor/doctrine/doctrine-module/bin/doctrine-module orm:convert-mapping --namespace="Application\\Entity\\" --filter="Feedback" --force  --from-database annotation ./module/Application/src/


./vendor/doctrine/doctrine-module/bin/doctrine-module orm:generate-entities ./module/Application/src/ --generate-annotations=true --filter="Feedback"
 */
namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * Word
 *
 * @ORM\Table(name="word")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Word")
 */
class Word extends Entity\Word
{

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
     * @var \Application\Model\Entity\WordsGroup
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\WordsGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_words_group", referencedColumnName="id")
     * })
     */
    private $fkWordsGroup;

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
     * @var \Application\Model\Entity\Speaker
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Speaker")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_speaker_target", referencedColumnName="id")
     * })
     */
    private $fkSpeakerTarget;

    /**
     * @var \Application\Model\Entity\Speaker
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Speaker")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_speaker_source", referencedColumnName="id")
     * })
     */
    private $fkSpeakerSource;


    /**
     * @var \Application\Model\Entity\Speaker
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Speaker")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_speaker_target_simple", referencedColumnName="id")
     * })
     */
    private $fkSpeakerTargetSimple;

    /**
     * @var \Application\Model\Entity\Speaker
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Speaker")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_speaker_source_simple", referencedColumnName="id")
     * })
     */
    private $fkSpeakerSourceSimple;

    /**
     * Set fkLanguageSource
     *
     * @param \Application\Model\Entity\Language $fkLanguageSource
     *
     * @return Word
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
     * @return Word
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
     * Set fkWordsGroup
     *
     * @param \Application\Model\Entity\WordsGroup $fkWordsGroup
     *
     * @return Word
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
     * Set fkUser
     *
     * @param \Application\Model\Entity\User $fkUser
     *
     * @return Word
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
     * Set fkSpeakerTarget
     *
     * @param \Application\Model\Entity\Speaker $fkSpeakerTarget
     *
     * @return Word
     */
    public function setFkSpeakerTarget(\Application\Model\Entity\Speaker $fkSpeakerTarget = null)
    {
        $this->fkSpeakerTarget = $fkSpeakerTarget;

        return $this;
    }

    /**
     * Get fkSpeakerTarget
     *
     * @return \Application\Model\Entity\Speaker
     */
    public function getFkSpeakerTarget()
    {
        return $this->fkSpeakerTarget;
    }


    /**
     * Set fkSpeakerSource
     *
     * @param \Application\Model\Entity\Speaker $fkSpeakerSource
     *
     * @return Word
     */
    public function setFkSpeakerSource(\Application\Model\Entity\Speaker $fkSpeakerSource = null)
    {
        $this->fkSpeakerSource = $fkSpeakerSource;

        return $this;
    }

    /**
     * Get fkSpeakerSource
     *
     * @return \Application\Model\Entity\Speaker
     */
    public function getFkSpeakerSource()
    {
        return $this->fkSpeakerSource;
    }


    /**
     * Set fkSpeakerTarget
     *
     * @param \Application\Model\Entity\Speaker $fkSpeakerTargetSimple
     *
     * @return Word
     */
    public function setFkSpeakerTargetSimple(\Application\Model\Entity\Speaker $fkSpeakerTargetSimple = null)
    {
        $this->fkSpeakerTargetSimple = $fkSpeakerTargetSimple;

        return $this;
    }

    /**
     * Get fkSpeakerTarget
     *
     * @return \Application\Model\Entity\Speaker
     */
    public function getFkSpeakerTargetSimple()
    {
        return $this->fkSpeakerTargetSimple;
    }


    /**
     * Set fkSpeakerSource
     *
     * @param \Application\Model\Entity\Speaker $fkSpeakerSourceSimple
     *
     * @return Word
     */
    public function setFkSpeakerSourceSimple(\Application\Model\Entity\Speaker $fkSpeakerSourceSimple = null)
    {
        $this->fkSpeakerSourceSimple = $fkSpeakerSourceSimple;

        return $this;
    }

    /**
     * Get fkSpeakerSource
     *
     * @return \Application\Model\Entity\Speaker
     */
    public function getFkSpeakerSourceSimple()
    {
        return $this->fkSpeakerSourceSimple;
    }
}
