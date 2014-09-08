<?php

namespace Application\Service;

use Application\Service\Speaker\Service as SpeakerEngine;
use Application\Model\Repository\Download as DownloadRepository;
use Doctrine\ORM\EntityManager;
use Application\Model\Entity\Download as DownloadEntity;
use \Application\Model\Entity\Word as WordEntity;
use \Application\Service\Store as StoreService;
use \Application\Model\Entity\StrategyItemSettings\Silent as SilentSettings;
use Application\Model\Entity\StrategyItem as StrategyItemEntity;
use Application\Model\Entity\Speaker as SpeakerEntity;
use \Application\Model\Mp3Tag\Mp3Tag;
use \Application\Model\Mp3Editor\Mp3Editor;

class Download
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var DownloadRepository
     */
    private $downloadRepository;

    /**
     * @var \Application\Service\Speaker
     */
    private $speakerService;

    /**
     * @var \Application\Service\Email
     */
    private $emailService;

    /**
     * @var StoreService
     */
    private $storeService;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param $speakerService
     * @param $storeService
     * @param $emailService
     */
    public function __construct(EntityManager $entityManager, $speakerService, $storeService, $emailService)
    {
        $this->entityManager      = $entityManager;
        $this->downloadRepository = $entityManager->getRepository('Application\Model\Entity\Download');
        $this->speakerService     = $speakerService;
        $this->storeService       = $storeService;
        $this->emailService       = $emailService;
    }

    public function getDownloads($userEntity)
    {
        return $this->downloadRepository->getDownloads($userEntity);
    }

    public function getDownloadsByStatus($status)
    {
        return $this->downloadRepository->getDownloadsByStatus($status);
    }

    public function proceedDownloads()
    {
        $downloadEntities = $this->getDownloadsByStatus(DownloadEntity::STATUS_INITIAL);

        /** @var DownloadEntity $downloadEntity */
        foreach ($downloadEntities as $downloadEntity) {

            $downloadItemPaths = [];
            $downloadEntity->setStatus(DownloadEntity::STATUS_IN_PROGRESS);
            $this->save($downloadEntity);

            $wordsEntities = $downloadEntity->getFkWordsGroup()->getWordList();
            if (!$wordsEntities->count()) {
                $downloadEntity->setStatus(DownloadEntity::STATUS_CANCELED);
                $this->save($downloadEntity);
                continue;
            }

            $deferWordsEntities = [];
            foreach ($wordsEntities as $wordsEntity) {

                $speakerSourceEntity = $wordsEntity->getFkSpeakerSource();
                $speakerTargetEntity = $wordsEntity->getFkSpeakerTarget();

                if ($speakerTargetEntity->getStatus() == SpeakerEntity::STATUS_IN_PROGRESS || $speakerTargetEntity->getStatus() == SpeakerEntity::STATUS_IN_PROGRESS) {
                    $deferWordsEntities[] = $wordsEntity;
                    continue;
                }

                if ($speakerSourceEntity->getStatus() == SpeakerEntity::STATUS_FRESH) {
                    $bites = $this->speakerService->createSound($speakerSourceEntity);
                    if (!$bites) {
                        continue;
                    }
                }
                if ($speakerTargetEntity->getStatus() == SpeakerEntity::STATUS_FRESH) {
                    $bites = $this->speakerService->createSound($speakerTargetEntity);
                    if (!$bites) {
                        continue;
                    }
                }

                $downloadItemPath = $this->createDownloadItem($downloadEntity, $wordsEntity);

                $downloadItemPaths[] = $downloadItemPath;
            }

            $downloadSize = $this->createArchive($downloadEntity, $downloadItemPaths);

            $downloadEntity->setWight($downloadSize);
            $downloadEntity->setStatus(DownloadEntity::STATUS_READY);

            $this->saveDownloadEntity($downloadEntity);

            $this->emailService->sendDownloadMessage($downloadEntity);
        }
    }

    /**
     * @param \Application\Model\Entity\Download $downloadEntity
     * @param \Application\Model\Entity\Word $wordEntity
     *
     * @return \Application\Model\Mp3Editor\Mp3Editor
     */
    private function createDownloadItem(DownloadEntity $downloadEntity, WordEntity $wordEntity)
    {
        $speakerSourceEntity = $wordEntity->getFkSpeakerSource();
        $speakerTargetEntity = $wordEntity->getFkSpeakerTarget();

        $sourceFile = $this->storeService->getSpeakPath($speakerSourceEntity->getId(), StoreService::TYPE_SPEAKER);
        $targetFile = $this->storeService->getSpeakPath($speakerTargetEntity->getId(), StoreService::TYPE_SPEAKER);

        $sourceMp3Editor = new Mp3Editor($sourceFile);
        $targetMp3Editor = new Mp3Editor($targetFile);

        $silenceFile = $this->storeService->getSilenceFile();
        $silenceMp3Editor = new Mp3Editor($silenceFile);

        $sourceMp3TagEditor = new Mp3Tag($sourceFile);
        $targetMp3TagEditor = new Mp3Tag($targetFile);

        $pieceCollection = [];

        $strategyItems = $downloadEntity->getFkStrategy()->getItems();
        /** @var StrategyItemEntity $strategyItemEntity */
        foreach ($strategyItems as $strategyItemEntity) {

            switch ($strategyItemEntity->getType()) {
                case StrategyItemEntity::TYPE_SOURCE:
                    // $pieceCollection[] = $sourceFile;
                    $pieceCollection[] = clone $sourceMp3Editor;
                    break;
                case StrategyItemEntity::TYPE_TARGET:
                    // $pieceCollection[] = $targetFile;
                    $pieceCollection[] = clone $targetMp3Editor;
                    break;
                case StrategyItemEntity::TYPE_SILENCE:
                    /** @var SilentSettings $settings */
                    $settings     = $strategyItemEntity->getSettings();
                    $silentSecond = 0;
                    switch ($settings->getType()) {
                        case SilentSettings::TYPE_SOURCE:
                            $silentSecond = $sourceMp3TagEditor->getPlaytimeInSeconds();
                            break;
                        case SilentSettings::TYPE_TARGET:
                            $silentSecond = $targetMp3TagEditor->getPlaytimeInSeconds();
                            break;
                        case SilentSettings::TYPE_DEFINED:
                            $silentSecond = $settings->getSeconds();
                            break;
                    }
                    if ($silentSecond) {
                        for ($i = 0; $i < $silentSecond; $i++) {
                            // $pieceCollection[] = $silenceFile;
                            $pieceCollection[] = clone $silenceMp3Editor;
                        }
                    }
                    break;
            }
        }
//var_export(count($pieceCollection)); exit();
        $this->glueItems($pieceCollection, $wordEntity);
        return $this->creteTag($wordEntity);
    }

    /**
     * @param $pieceCollection
     * @param \Application\Model\Entity\Word $wordEntity
     *
     * @return array
     */
    protected function glueItems($pieceCollection, WordEntity $wordEntity)
    {
        $downloadItemPath = $this->storeService->getSpeakPath($wordEntity->getId(), StoreService::TYPE_DOWNLOAD_ITEM);
/*
        $command = sprintf('ffmpeg -y -i "concat:%s" -acodec copy %s', implode('|', $pieceCollection), $downloadItemPath);
        system($command);
*/
        file_put_contents($downloadItemPath, null);

        $resultMp3Editor = new Mp3Editor($downloadItemPath);
        foreach ($pieceCollection as $mp3Editor) {
            $resultMp3Editor->mergeBehind($mp3Editor);
        }
        $resultMp3Editor->savefile($downloadItemPath);
    }

    /**
     * @param \Application\Model\Entity\Word $wordEntity
     *
     * @return array
     */
    protected function creteTag(WordEntity $wordEntity)
    {
        $downloadItemPath = $this->storeService->getSpeakPath($wordEntity->getId(), StoreService::TYPE_DOWNLOAD_ITEM);

        $tag = sprintf('%s - %s', $wordEntity->getSource(), $wordEntity->getTarget());

        $mp3TagEditor = new Mp3Tag($downloadItemPath);
        $mp3TagEditor->editTag($tag);

        return array($downloadItemPath, $tag);
    }

    /**
     * @param DownloadEntity $downloadEntity
     *
     * @return DownloadEntity|null
     */
    public function save(DownloadEntity $downloadEntity)
    {
        $this->entityManager->persist($downloadEntity);
        $this->entityManager->flush();
        return $downloadEntity;
    }

    /**
     * @param integer $downloadID
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return DownloadEntity
     */
    public function getDownloadById($downloadID, $userEntity)
    {
        return $this->downloadRepository->getDownloadById($downloadID, $userEntity);
    }

    /**
     * @param string $downloadHash
     *
     * @return DownloadEntity
     */
    public function getDownloadByHash($downloadHash)
    {
        return $this->downloadRepository->getDownloadByHash($downloadHash);
    }


    /**
     * @param DownloadEntity $downloadEntity
     *
     * @throws \Exception
     */
    public function dropDownload($downloadEntity)
    {
        $downloadFile = $this->storeService->getSpeakPath($downloadEntity->getId(), StoreService::TYPE_DOWNLOAD);
        if ($downloadEntity->getStatus() == DownloadEntity::STATUS_READY && !@unlink($downloadFile)) {
            throw new \Exception('Фаил загрузки не может быть удален');
        }
        ;

        $this->entityManager->remove($downloadEntity);
        $this->entityManager->flush();
    }

    /**
     * @param DownloadEntity $downloadEntity
     *
     * @return DownloadEntity
     */
    public function saveDownloadEntity($downloadEntity)
    {
        $downloadEntity->setUpdatedAt(new \DateTime());
        $downloadEntity->setHash(md5($downloadEntity->getFkUser()->getEmail() . 'TRY'));
        $this->entityManager->persist($downloadEntity);
        $this->entityManager->flush();

        return $downloadEntity;
    }

    protected function createArchive(DownloadEntity $downloadEntity, $downloadItemPaths)
    {
        $downloadPath = $this->storeService->getSpeakPath($downloadEntity->getId(), StoreService::TYPE_DOWNLOAD);

        $zip = new \ZipArchive();
        // open archive
        if (!$zip->open($downloadPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            throw new \Application\Model\CliException("Could not open archive ($downloadPath)");
        }

        foreach ($downloadItemPaths as $downloadItemPath) {
            list($itemPath, $fileName) = $downloadItemPath;
            $fileName      = $fileName . $this->storeService->getExtension(StoreService::TYPE_DOWNLOAD_ITEM);
            $fileNameToAdd = preg_replace('#[^\pL\pN.-\s]+#ui', '', $fileName);
            $fileNameToAdd = @iconv("utf-8", 'CP866//TRANSLIT//IGNORE', $fileNameToAdd);
            if (!$zip->addFile($itemPath, $fileNameToAdd)) {
                throw new \Application\Model\CliException("Could not add file: $fileName ($itemPath)");
            }
            ;
        }

        $zip->close();
        unset($zip);

        return filesize($downloadPath);
    }
}