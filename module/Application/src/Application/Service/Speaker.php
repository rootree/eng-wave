<?php

namespace Application\Service;

use \Application\Model\Entity\Word as WordsEntity;
use \Application\Service\Speaker as SpeakerEngine;
use \Application\Model\Mp3Tag\Mp3Tag;
use \Application\Model\Mp3Editor\Mp3Editor;
use Application\Model\Repository\Speaker as SpeakerRepository;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\Language as LanguageEntity;
use Application\Model\Entity\Speaker as SpeakerEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Doctrine\ORM\EntityManager;
use Application\Model\CliException as CliException;

class Speaker
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SpeakerRepository
     */
    private $speakerRepository;

    /**
     * @var \Application\Service\Store
     */
    private $storeService;

    private $zendLogService;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \Application\Service\Store $storeService
     */
    public function __construct(EntityManager $entityManager, $storeService, $zendLogService)
    {
        $this->storeService      = $storeService;
        $this->zendLogService    = $zendLogService;
        $this->entityManager     = $entityManager;
        $this->speakerRepository = $entityManager->getRepository('Application\Model\Entity\Speaker');
    }


    public function getSpeaksForProceed()
    {
        $speakerEntities = $this->speakerRepository->getSpeakersByStatus(SpeakerEntity::STATUS_FRESH);
        /** @var SpeakerEntity $speakerEntity */
        foreach ($speakerEntities as $speakerEntity) {
            $this->createSound($speakerEntity);
        }
    }

    /**
     * @param \Application\Model\Entity\Speaker $speakerEntity
     *
     * @throws \Exception
     * @return int|bool
     */
    public function createSound(SpeakerEntity $speakerEntity)
    {
        $speakerEntity->setStatus(SpeakerEntity::STATUS_IN_PROGRESS);
        $this->save($speakerEntity);

        try {

            $bites = $this->createSoundFile($speakerEntity);

            $speakerEntity->setStatus(SpeakerEntity::STATUS_READY);
            $this->save($speakerEntity);

            return $bites;

        } catch (\Exception $e) {

            $speakerEntity->setStatus(SpeakerEntity::STATUS_FRESH);
            $this->save($speakerEntity);

            return false;
        }
    }

    private function createSoundFile(SpeakerEntity $speakerEntity)
    {
        /** @var SpeakerEngine\SpeakerAbstract $sourceSpeaker */
        $sourceSpeaker = SpeakerEngine\Factory::getSpeaker(SpeakerEngine\Factory::TYPE_YANDEX, $speakerEntity->getFkLanguage()->getIso2());
        $sourceSpeaker->setLogger($this->zendLogService);

        $sourceFileContent = $sourceSpeaker->getWordsFileContent($speakerEntity->getWord());
        if (!$sourceFileContent) {
            throw new CliException(sprintf('Can not get speaker file for "%s" word', $speakerEntity->getWord()));
        }

        $pathToSourceFile = $this->storeService->getSpeakPath($speakerEntity->getId(), \Application\Service\Store::TYPE_SPEAKER);

        if (file_exists($pathToSourceFile) && !is_writable($pathToSourceFile)) {
            throw new CliException(sprintf('Can not write to %s', $pathToSourceFile));
        }

        $bites = @file_put_contents($pathToSourceFile, $sourceFileContent);
        if ($bites === false) {
            throw new CliException(sprintf('Can not write to %s, do not why', $pathToSourceFile));
        }

        return $bites;
    }

    /**
     * @param $word
     * @param \Application\Model\Entity\Language $language
     *
     * @return \Application\Model\Entity\Speaker
     */
    public function createSpeaker($word, LanguageEntity $language)
    {
        $hash = $this->getHash($word, $language->getIso2());
        $existsSpeakerEntity = $this->speakerRepository->findOneBy(['hash' => $hash]);
        if ($existsSpeakerEntity) {
            return $existsSpeakerEntity;
        }

        $newSpeakerEntity = new SpeakerEntity();
        $newSpeakerEntity->setFkLanguage($language);
        $newSpeakerEntity->setStatus(SpeakerEntity::STATUS_FRESH);
        $newSpeakerEntity->setHash($hash);
        $newSpeakerEntity->setWord($word);

        $this->entityManager->persist($newSpeakerEntity);
        //$this->entityManager->flush();

        return $newSpeakerEntity;
    }

    private function getHash($word, $languageIso2)
    {
        return md5($word . $languageIso2);
    }

    /**
     * @param SpeakerEntity $speakerEntity
     *
     * @return SpeakerEntity|null
     */
    public function save(SpeakerEntity $speakerEntity)
    {
        $this->entityManager->persist($speakerEntity);
        $this->entityManager->flush();
        return $speakerEntity;
    }
}