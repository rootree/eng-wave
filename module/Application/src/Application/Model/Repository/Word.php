<?php

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;

class Word extends EntityRepository
{
    /**
     * @param WordsGroupEntity $wordsGroupEntity
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return array
     */
    public function getWords($wordsGroupEntity, $userEntity)
    {
        return $this->findBy([
            'fkWordsGroup' => $wordsGroupEntity,
            'fkUser'       => $userEntity
        ]);
    }

    /**
     * @param integer $wordID
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return \Application\Model\Entity\Word
     */
    public function getWordById($wordID, $userEntity)
    {
        return $this->findOneBy([
            'id'     => $wordID,
            'fkUser' => $userEntity,
        ]);
    }
}
