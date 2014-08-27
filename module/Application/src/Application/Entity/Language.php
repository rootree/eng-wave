<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\MappedSuperclass
 */
abstract class Language
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
     * @ORM\Column(name="iso2", type="string", length=2, nullable=false)
     */
    private $iso2 = '';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=30, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="title_en", type="string", length=30, nullable=false)
     */
    private $titleEn = '';



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
     * Set iso2
     *
     * @param string $iso2
     *
     * @return Language
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;

        return $this;
    }

    /**
     * Get iso2
     *
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Language
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
     * Set titleEn
     *
     * @param string $titleEn
     *
     * @return Language
     */
    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    /**
     * Get titleEn
     *
     * @return string
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }
}
