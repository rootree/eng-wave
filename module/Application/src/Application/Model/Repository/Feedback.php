<?php

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;

class Feedback extends EntityRepository
{

}
