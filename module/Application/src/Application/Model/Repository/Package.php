<?php

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Package as PackageEntity;
use Application\Model\Entity\User as UserEntity;

class Package extends EntityRepository
{
    /**
     * @return array
     */
    public function getPackages()
    {
        return $this->findAll();
    }

    /**
     * @param integer $packageID
     *
     * @return PackageEntity[]
     */
    public function getPackageById($packageID)
    {
        return $this->findOneBy(['id' => $packageID]);
    }

    /**
     * @return PackageEntity[]
     */
    public function getDefaultPackages()
    {
        return $this->findBy(['isBase' => 1]);
    }
}
