<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Download
 *
 * @ORM\Table(name="download", indexes={@ORM\Index(name="downloads_fk_user", columns={"fk_user"}), @ORM\Index(name="downloads_fk_words_group", columns={"fk_words_group"}), @ORM\Index(name="downloads_fk_strategy", columns={"fk_strategy"})})
 * @ORM\MappedSuperclass
 */
abstract class Download
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
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="wight", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $wight = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32, nullable=true)
     */
    private $hash;




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
     * @return Download
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
     * @return Download
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
     * Set status
     *
     * @param integer $status
     *
     * @return Download
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
     * Set wight
     *
     * @param integer $wight
     *
     * @return Download
     */
    public function setWight($wight)
    {
        $this->wight = $wight;

        return $this;
    }

    /**
     * Get wight
     *
     * @return integer
     */
    public function getWight()
    {
        return $this->wight;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Download
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

}
