<?php

namespace Application\Service;

use Application\Model\Repository\Word as WordRepository;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\Language as LanguageEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Doctrine\ORM\EntityManager;

class Word
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var WordRepository
     */
    private $wordRepository;

    /**
     * @var \Application\Service\Speaker
     */
    private $speakerService;

    public function __construct(EntityManager $entityManager, $speakerService)
    {
        $this->entityManager = $entityManager;
        $this->wordRepository = $entityManager->getRepository('Application\Model\Entity\Word');
        $this->speakerService = $speakerService;
    }

    /**
     * @param WordsGroupEntity $wordsGroupEntity
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return WordEntity[]
     */
    public function getAllWords($wordsGroupEntity, $userEntity )
    {
        return $this->wordRepository->getWords($wordsGroupEntity, $userEntity);
    }

    /**
     * @param integer $wordID
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return WordEntity
     */
    public function getWordById($wordID, $userEntity)
    {
        return $this->wordRepository->getWordById($wordID, $userEntity);
    }

    /**
     * @param WordEntity $wordEntity
     */
    public function dropWord($wordEntity)
    {
        $this->entityManager->remove($wordEntity);
        $this->entityManager->flush();
    }

    /**
     * @param integer|array $wordIDs
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return WordEntity
     */
    public function dropSeveralWords($wordIDs, $userEntity)
    {
        $wordIDs = (array) $wordIDs;

        $query = $this->entityManager->createQuery(
            'DELETE Application\\Model\\Entity\\Word word
             WHERE word.fkUser = :user AND word.id IN (:wordIDs)'
        );
        $query->setParameter('user', $userEntity);
        $query->setParameter('wordIDs', $wordIDs);
        $query->execute();

        // $this->entityManager->flush();
        // Clear internal state, so entities will be re-read from DB
        //$this->entityManager->clear();
    }

    /**
     * @param integer|array $wordIDs
     * @param $wordsGroupEntity
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return WordEntity
     */
    public function moveSeveralWords($wordIDs, $wordsGroupEntity, $userEntity)
    {
        $wordIDs = (array) $wordIDs;

        $query = $this->entityManager->createQuery(
            'UPDATE Application\\Model\\Entity\\Word word
             SET word.fkWordsGroup = :group
             WHERE word.fkUser = :user AND word.id IN (:wordIDs)'
        );
        $query->setParameter('group', $wordsGroupEntity);
        $query->setParameter('user', $userEntity);
        $query->setParameter('wordIDs', $wordIDs);
        $query->execute();

        // $this->entityManager->flush();
        // Clear internal state, so entities will be re-read from DB
        //$this->entityManager->clear();
    }

    /**
     * @param \Application\Model\Entity\Word $wordEntity
     *
     * @return WordEntity|null
     */
    public function save(WordEntity $wordEntity)
    {
        $wordEntity->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($wordEntity);
        $this->entityManager->flush();
        return $wordEntity;
    }


    /**
     * @param string $word
     * @param LanguageEntity $language
     *
     * @return \Application\Model\Entity\Speaker
     */
    public function getSpeaker($word, LanguageEntity $language)
    {
        $speakerEntity = $this->speakerService->createSpeaker($word, $language);
        return $speakerEntity;
    }
}
