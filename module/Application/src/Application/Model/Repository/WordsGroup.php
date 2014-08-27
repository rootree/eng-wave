<?php

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;

class WordsGroup extends EntityRepository
{
    /**
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return array
     */
    public function getGroups($userEntity)
    {
        return $this->findBy([
            'fkUser' => $userEntity
        ]);
    }

    /**
     * @param integer $groupID
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return WordsGroupEntity
     */
    public function getWordsGroupById($groupID, $userEntity)
    {
        return $this->findOneBy(['id' => $groupID, 'fkUser' => $userEntity ]);
    }
}
