<?php

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\PackageUser as PackageUserEntity;
use Application\Model\Entity\Package as PackageEntity;
use Application\Model\Entity\User as UserEntity;

class PackageUser extends EntityRepository
{
    /**
     * @param UserEntity $userEntity
     * @param PackageEntity $packageEntity
     *
     * @return PackageUserEntity
     */
    public function getRelation($packageEntity, $userEntity)
    {
        return $this->findOneBy([
            'fkUser' => $userEntity,
            'fkPackage' => $packageEntity,
        ]);
    }


}
