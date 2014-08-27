<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Word
 *
 * @ORM\Table(name="word", indexes={@ORM\Index(name="user", columns={"fk_user"}), @ORM\Index(name="group", columns={"fk_words_group"}), @ORM\Index(name="FK_language_souce", columns={"fk_language_source"}), @ORM\Index(name="FK_language_target", columns={"fk_language_target"}), @ORM\Index(name="word_fk_speaker_source", columns={"fk_speaker_source"}), @ORM\Index(name="word_fk_speaker_target", columns={"fk_speaker_target"})})
 * @ORM\MappedSuperclass
 */
abstract class Word
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=1000, nullable=true)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="target", type="string", length=1000, nullable=true)
     */
    private $target;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Word
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Word
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Word
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set target
     *
     * @param string $target
     *
     * @return Word
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

}
