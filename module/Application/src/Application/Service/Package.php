<?php

namespace Application\Service;

use \Application\Model\Speaker as SpeakerEngine;
use \Application\Model\Entity\Package as PackageEntity;
use \Application\Model\Entity\User as UserEntity;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\PackageWord as PackageWordEntity;
use Application\Model\Entity\PackageUser as PackageUserEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Application\Model\Repository\Package as PackageRepository;
use Application\Model\Repository\PackageUser as PackageUserRepository;
use Doctrine\ORM\EntityManager;
use \Application\Service\Store as StoreService;
use \Application\Model\Entity\StrategyItemSettings\Silent as SilentSettings;

class Package
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PackageRepository
     */
    private $packageRepository;

    /**
     * @var PackageUserRepository
     */
    private $packageUserRepository;

    /**
     * @var \Application\Service\Word
     */
    private $wordService;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param $wordService
     */
    public function __construct(EntityManager $entityManager, $wordService)
    {
        $this->entityManager         = $entityManager;
        $this->packageRepository     = $entityManager->getRepository('Application\Model\Entity\Package');
        $this->packageUserRepository = $entityManager->getRepository('Application\Model\Entity\PackageUser');
        $this->wordService           = $wordService;
    }

    public function getPackages()
    {
        return $this->packageRepository->getPackages();
    }

    public function getPackageById($packageID)
    {
        return $this->packageRepository->getPackageById($packageID);
    }

    /**
     * @return PackageEntity[]
     */
    public function getDefaultPackages()
    {
        return $this->packageRepository->getDefaultPackages();
    }

    /**
     * @param PackageEntity $packageEntity
     * @param UserEntity $userEntity
     *
     * @return PackageUserEntity|null
     */
    public function isPackageInstalled($packageEntity, $userEntity)
    {
        return $this->packageUserRepository->getRelation($packageEntity, $userEntity);
    }

    /**
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsersPackages(UserEntity $userEntity)
    {
        return $userEntity->getPackageRelationsList();
    }

    /**
     * @param \Application\Model\Entity\Package $packageEntity
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return bool|WordsGroupEntity
     * @throws \Exception
     */
    public function install(PackageEntity $packageEntity, UserEntity $userEntity)
    {
        if ($this->isPackageInstalled($packageEntity, $userEntity)) {
            return false;
        }

        $this->entityManager->beginTransaction();

        try {

            $wordsGroupEntity = new WordsGroupEntity();
            $wordsGroupEntity->setCreatedAt(new \DateTime());
            $wordsGroupEntity->setUpdatedAt(new \DateTime());
            $wordsGroupEntity->setFkUser($userEntity);
            $wordsGroupEntity->setTitle($packageEntity->getTitle());

            $this->entityManager->persist($wordsGroupEntity);

            $wordPackageEntities = $packageEntity->getWordsList();

            /** @var PackageWordEntity $wordPackageEntity */
            foreach ($wordPackageEntities as $wordPackageEntity) {

                $wordEntity = new WordEntity();

                $wordEntity->setCreatedAt(new \DateTime());
                $wordEntity->setUpdatedAt(new \DateTime());
                $wordEntity->setFkLanguageSource($wordPackageEntity->getFkLanguageSource());
                $wordEntity->setFkLanguageTarget($wordPackageEntity->getFkLanguageTarget());
                $wordEntity->setFkUser($userEntity);
                $wordEntity->setSource($wordPackageEntity->getSource());
                $wordEntity->setTarget($wordPackageEntity->getTarget());

                $wordEntity->setFkWordsGroup($wordsGroupEntity);

                $wordEntity->setFkSpeakerSource(
                    $this->wordService->getSpeaker($wordEntity->getSource(), $wordEntity->getFkLanguageSource())
                );
                $wordEntity->setFkSpeakerTarget(
                    $this->wordService->getSpeaker($wordEntity->getTarget(), $wordEntity->getFkLanguageTarget())
                );

                $this->entityManager->persist($wordEntity);
            }

            $packageUserEntity = new PackageUserEntity();

            $packageUserEntity->setCreatedAt(new \DateTime());
            $packageUserEntity->setUpdatedAt(new \DateTime());
            $packageUserEntity->setFkPackage($packageEntity);
            $packageUserEntity->setFkUser($userEntity);

            $this->entityManager->persist($packageUserEntity);

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        return $wordsGroupEntity;
    }
}