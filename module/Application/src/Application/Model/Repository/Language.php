<?php

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class Language extends EntityRepository
{
    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->findAll();
    }
}
