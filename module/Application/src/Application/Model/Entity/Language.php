<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity as Entity;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="Application\Model\Repository\Language")
 */
class Language extends Entity\Language
{

}
