<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Package
 *
 * @ORM\Table(name="package", indexes={@ORM\Index(name="parckage_fk_language_target", columns={"fk_language_target"}), @ORM\Index(name="parckage_fk_language_source", columns={"fk_language_source"})})
 * @ORM\MappedSuperclass
 */
abstract class Package
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=250, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="desc", type="text", length=65535, nullable=false)
     */
    private $desc;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $sort = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_base", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $isBase = '0';

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
     * Set title
     *
     * @param string $title
     *
     * @return Package
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set desc
     *
     * @param string $desc
     *
     * @return Package
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get desc
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return Package
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Package
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set isBase
     *
     * @param integer $isBase
     *
     * @return Package
     */
    public function setIsBase($isBase)
    {
        $this->isBase = $isBase;

        return $this;
    }

    /**
     * Get isBase
     *
     * @return integer
     */
    public function getIsBase()
    {
        return $this->isBase;
    }
}
