<?php

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;

class Strategy extends EntityRepository
{
    public function getAll($userEntity)
    {
        return $this->findBy([
            'fkUser' => $userEntity
        ],['createdAt' => 'DESC']);
    }

    /**
     * @param integer $strategyID
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return \Application\Model\Entity\Strategy
     */
    public function getStrategyById($strategyID, $userEntity)
    {
        return $this->findOneBy([
            'id'     => $strategyID,
            'fkUser' => $userEntity,
        ]);
    }
}
