<?php

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Download as DownloadEntity;
use Zend\Feed\Exception\RuntimeException;

class Download extends EntityRepository
{
    /**
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return array
     */
    public function getDownloads($userEntity)
    {
        return $this->findBy([
            'fkUser' => $userEntity
        ]);
    }

    /**
     * @param integer $downloadID
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return DownloadEntity
     */
    public function getDownloadById($downloadID, $userEntity)
    {
        return $this->findOneBy(['id' => $downloadID, 'fkUser' => $userEntity ]);
    }

    /**
     * @param string $downloadHash
     *
     * @return DownloadEntity
     */
    public function getDownloadByHash($downloadHash)
    {
        return $this->findOneBy(['hash' => $downloadHash]);
    }

    /**
     * @param int $status
     *
     * @return DownloadEntity[]
     */
    public function getDownloadsByStatus($status)
    {
        return $this->findBy([
            'status' => $status
        ]);
    }
}
