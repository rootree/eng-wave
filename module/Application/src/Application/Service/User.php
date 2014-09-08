<?php

namespace Application\Service;

use Application\Model\Repository\User as UserRepository;
use Application\Model\Entity\User as UserEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Doctrine\ORM\EntityManager;

class User
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('Application\Model\Entity\User');
    }

    /**
     * @param UserEntity $userEntity
     *
     * @return UserEntity
     */
    public function save($userEntity)
    {
        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
        return $userEntity;
    }

    /**
     * @param $initialWordsGroupEntity
     *
     * @return UserEntity
     */
    public function settingsTemplate($initialWordsGroupEntity)
    {
        $settings = new \stdClass();
        $settings->currentGroup = $initialWordsGroupEntity->getId();
        return $settings;
    }

    /**
     * @param $initialWordsGroupEntity
     *
     * @return UserEntity
     */
    public function getUserByEmail($email)
    {
        return $this->userRepository->findOneBy([
            'email' => $email,
        ]);
    }

    /**
     * @param $password
     *
     * @return UserEntity
     */
    public function generatePassword($password)
    {
        return md5($password);
    }
}
