<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Speaker
 *
 * @ORM\Table(name="speaker", indexes={@ORM\Index(name="speaker_fk_language", columns={"fk_language"})})
 * @ORM\MappedSuperclass
 */
abstract class Speaker
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
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32, nullable=false)
     */
    private $hash = '';

    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", length=1000, nullable=false)
     */
    private $word = '';


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
     * Set status
     *
     * @param integer $status
     *
     * @return Speaker
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Speaker
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set word
     *
     * @param string $word
     *
     * @return Speaker
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

}
