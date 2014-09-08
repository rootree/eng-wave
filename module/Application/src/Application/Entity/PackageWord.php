<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PackageWord
 *
 * @ORM\Table(name="package_word", indexes={@ORM\Index(name="package_word_fk_language_source", columns={"fk_language_source"}), @ORM\Index(name="package_word_fk_language_target", columns={"fk_language_target"}), @ORM\Index(name="package_word_fk_package", columns={"fk_package"})})
 * @ORM\MappedSuperclass
 */
abstract class PackageWord
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
     * @ORM\Column(name="source", type="string", length=1000, nullable=false)
     */
    private $source = '';

    /**
     * @var string
     *
     * @ORM\Column(name="target", type="string", length=1000, nullable=false)
     */
    private $target = '';


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
     * Set source
     *
     * @param string $source
     *
     * @return PackageWord
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
     * @return PackageWord
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
