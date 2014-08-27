<?php

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Strategy as StrategyEntity;

class Speaker extends EntityRepository
{
    /**
     * @param int $status
     *
     * @return StrategyEntity[]
     */
    public function getSpeakersByStatus($status)
    {
        return $this->findBy([
            'status' => $status
        ]);
    }
}
