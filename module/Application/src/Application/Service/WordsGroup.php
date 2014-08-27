<?php

namespace Application\Service;

use Application\Model\Repository\WordsGroup as WordsGroupRepository;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Doctrine\ORM\EntityManager;

class WordsGroup
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var WordsGroupRepository
     */
    private $wordsGroupRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->wordsGroupRepository = $entityManager->getRepository('Application\Model\Entity\WordsGroup');
    }

    /**
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return WordEntity[]
     */
    public function getAllGroups($userEntity)
    {
        return $this->wordsGroupRepository->getGroups($userEntity);
    }

    /**
     * @param integer $groupID
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return WordsGroupEntity
     */
    public function getWordsGroupById($groupID, $userEntity)
    {
        return $this->wordsGroupRepository->getWordsGroupById($groupID, $userEntity);
    }

    /**
     * @param WordsGroupEntity $wordsGroupEntity
     *
     * @return WordsGroupEntity
     */
    public function saveGroupEntity($wordsGroupEntity)
    {
        $wordsGroupEntity->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($wordsGroupEntity);
        $this->entityManager->flush();

        return $wordsGroupEntity;
    }

    /**
     * @param WordsGroupEntity $wordsGroupEntity
     */
    public function dropGroup($wordsGroupEntity)
    {
        $this->entityManager->remove($wordsGroupEntity);
        $this->entityManager->flush();
    }
}
