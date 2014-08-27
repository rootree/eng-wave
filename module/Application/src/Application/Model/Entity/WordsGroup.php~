<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * WordsGroup
 * @ORM\Table(name="words_group")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\WordsGroup")
 */
class WordsGroup extends Entity\WordsGroup
{

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fk_user", referencedColumnName="id")
     * })
     */
    protected $fkUser;


    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Model\Entity\Word", mappedBy="fkWordsGroup")
     * @ORM\JoinColumn(name="fk_words_group", referencedColumnName="id")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $wordList;


    public function __construct()
    {
        $this->wordList = new ArrayCollection();
    }


    /**
     * Get wordList
     *
     * @return \Application\Model\Entity\Word[]
     */
    public function getWordList()
    {
        return $this->wordList;
    }

    /**
     * Set wordList
     *
     * @param \Application\Model\Entity\Word[] $wordList
     * @return WordsGroup
     */
    public function setWordList($wordList)
    {
        $this->wordList = $wordList;
        return $this;
    }

    /**
     * Set fkUser
     *
     * @param \Application\Model\Entity\User $fkUser
     *
     * @return WordsGroup
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
     * Add wordList
     *
     * @param \Application\Model\Entity\Word $wordList
     *
     * @return WordsGroup
     */
    public function addWordList(\Application\Model\Entity\Word $wordList)
    {
        $this->wordList[] = $wordList;

        return $this;
    }

    /**
     * Remove wordList
     *
     * @param \Application\Model\Entity\Word $wordList
     */
    public function removeWordList(\Application\Model\Entity\Word $wordList)
    {
        $this->wordList->removeElement($wordList);
    }
}
