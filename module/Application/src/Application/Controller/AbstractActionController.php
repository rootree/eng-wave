<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\Json\Json;
use Zend\Stdlib\ArrayUtils;
use Api\Model\Exception as ApiException;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Application\Model\Entity\Language as LanguageEntity;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\Download as DownloadEntity;
use Application\Model\Entity\Speaker as SpeakerEntity;
use Application\Model\Entity\Strategy as StrategyEntity;
use Application\Model\Entity\StrategyItem as StrategyItemEntity;
use Application\Service\Store as StoreService;

abstract class AbstractActionController extends ZendAbstractActionController
{
    const DATETIME_FORMAT = DATE_ATOM;

    protected function initGlobalSettings()
    {
        /** @var \Application\Service\Language $languageService  */
        $languageService = $this->getServiceLocator()->get('Application\Service\Language');
        $languageEntities = $languageService->getAllLanguages();

        $globalSettings['languages'] = $this->prepareLanguages($languageEntities);
        $globalSettings['CSRF']      = $this->getCsrfHash();

        $this->layout()->globalSettings = Json::encode($globalSettings);
    }

    protected function getCsrfHash()
    {
        $csrf = new \Zend\Validator\Csrf(['name' => 'csrf_token']);
        return $csrf->getHash();
    }

    protected function initUserSettings()
    {
        $this->layout()->userSettings = Json::encode($this->getUserJSON());
    }

    /**
     * @return \Zend\View\Model\JsonModel
     */
    protected function getUserJSON()
    {
        $user = ['authenticated' => 0];
        /** @var \Application\Model\Entity\User $userEntity  */

        $authService = $this->serviceLocator->get('Zend\Authentication\AuthenticationService');

        if ($authService->hasIdentity()) {

            $userEntity = $this->identity();

            /** @var \Application\Service\WordsGroup $wordsGroupService  */
            $wordsGroupService = $this->getServiceLocator()->get('Application\Service\WordsGroup');
            $groupEntities = $wordsGroupService->getAllGroups($userEntity);

            /** @var \Application\Service\Strategy $strategyService  */
            $strategyService = $this->getServiceLocator()->get('Application\Service\Strategy');
            $strategyEntities = $strategyService->getAllStrategies($userEntity);

            /** @var \Application\Service\Download $downloadService  */
            $downloadService = $this->getServiceLocator()->get('Application\Service\Download');
            $downloadEntities = $downloadService->getDownloads($userEntity);

            $user['authenticated'] = 1;
            $user['readyToDownload'] = $this->countReadyToDownload($downloadEntities);
            $user['strategies']    = $this->prepareStrategies($strategyEntities);
            $user['downloads']     = $this->prepareDownloads($downloadEntities);
            $user['settings']      = $userEntity->getSettings();
            $user['groups']        = $this->prepareGroups($groupEntities);
            $user['email']         = $userEntity->getEmail();
        }

        return ($user);
    }

    protected function prepareGroups($groupEntities)
    {
        $groupsResponse = [];
        /** @var WordsGroupEntity $groupEntity */
        foreach ($groupEntities as $groupEntity) {
            $groupsResponse[] = $this->prepareGroup($groupEntity);
        }
        return $groupsResponse;
    }

    protected function prepareStrategies($strategiesEntities)
    {
        $strategiesResponse = [];
        /** @var StrategyEntity $strategyEntity */
        foreach ($strategiesEntities as $strategyEntity) {
            $strategiesResponse[] = $this->prepareStrategy($strategyEntity);
        }
        return $strategiesResponse;
    }

    /**
     * @param WordsGroupEntity $groupEntity
     *
     * @return array
     */
    protected function prepareGroup($groupEntity)
    {
        return [
            'id'    => $groupEntity->getId(),
            'title' => $groupEntity->getTitle(),
            'count' => $groupEntity->getWordList()->count(),
        ];
    }

    /**
     * @param $languageEntities
     *
     * @return array
     */
    protected function prepareLanguages($languageEntities)
    {
        $languagesResponse = [];
        /** @var LanguageEntity $languageEntity */
        foreach ($languageEntities as $languageEntity) {
            $languagesResponse[] = [
                'id'      => $languageEntity->getId(),
                'title'   => $languageEntity->getTitle(),
                'titleEn' => $languageEntity->getTitleEn(),
                'iso'     => $languageEntity->getIso2(),
            ];
        }
        return $languagesResponse;
    }

    /**
     * @param $wordsEntities
     *
     * @return array
     */
    protected function prepareWords($wordsEntities)
    {
        $wordsResponse = [];
        /** @var WordEntity $wordsEntity */
        foreach ($wordsEntities as $wordsEntity) {
            $wordsResponse[] = $this->prepareWord($wordsEntity);
        }
        return $wordsResponse;
    }

    /**
     * @param $downloadEntities
     *
     * @return array
     */
    protected function prepareDownloads($downloadEntities)
    {
        $downloadsResponse = [];
        /** @var DownloadEntity $downloadEntity */
        foreach ($downloadEntities as $downloadEntity) {
            $downloadsResponse[] = $this->prepareDownload($downloadEntity);
        }
        return $downloadsResponse;
    }

    /**
     * @param $downloadEntities
     *
     * @return array
     */
    protected function countReadyToDownload($downloadEntities)
    {
        $downloadsCounter = 0;
        /** @var DownloadEntity $downloadEntity */
        foreach ($downloadEntities as $downloadEntity) {
            if ($downloadEntity->getStatus() == DownloadEntity::STATUS_READY) {
                $downloadsCounter++;
            }
        }
        return $downloadsCounter;
    }

    /**
     * @param WordEntity $wordsEntity
     *
     * @return array
     */
    protected function prepareWord($wordsEntity)
    {
        $storeService = $this->getServiceLocator()->get('Application\Service\Store');

        return [
            'id'               => $wordsEntity->getId(),
            'fkWordsGroup'     => $wordsEntity->getFkWordsGroup()->getId(),
            'source'           => $wordsEntity->getSource(),
            'sourceLang'       => $wordsEntity->getFkLanguageSource()->getIso2(),
            'fkLanguageSource' => $wordsEntity->getFkLanguageSource()->getId(),
            'target'           => $wordsEntity->getTarget(),
            'targetLang'       => $wordsEntity->getFkLanguageTarget()->getIso2(),
            'fkLanguageTarget' => $wordsEntity->getFkLanguageTarget()->getId(),
            'sourceURL'        => $wordsEntity->getFkSpeakerSource()->getStatus() == SpeakerEntity::STATUS_READY
                ? $storeService->getSpeakURL($wordsEntity->getFkSpeakerSource()->getId(), StoreService::TYPE_SPEAKER)
                : null,
            'targetURL'        => $wordsEntity->getFkSpeakerTarget()->getStatus() == SpeakerEntity::STATUS_READY
                ? $storeService->getSpeakURL($wordsEntity->getFkSpeakerTarget()->getId(), StoreService::TYPE_SPEAKER)
                : null,
        ];
    }

    /**
     * @param DownloadEntity $downloadEntity
     *
     * @return array
     */
    protected function prepareDownload($downloadEntity)
    {
        $strategy = $downloadEntity->getFkStrategy();
        $wordsGroup = $downloadEntity->getFkWordsGroup();
        return [
            'id'        => $downloadEntity->getId(),
            'group'     => $wordsGroup ? $wordsGroup->getTitle() : null,
            'groupCount'=> $wordsGroup ? $wordsGroup->getWordList()->count() : null,
            'groupID'   => $wordsGroup ? $wordsGroup->getId() : null,
            'strategy'  => $strategy ? $strategy->getTitle() : null,
            'status'    => $downloadEntity->getStatus(),
            'createdAt' => $downloadEntity->getCreatedAt()->format(self::DATETIME_FORMAT),
            'legacy'    => $downloadEntity->getLegacy(),
            'wight'     => $this->formatSizeUnits($downloadEntity->getWight()),
        ];
    }

    /**
     * @param StrategyEntity $strategyEntity
     *
     * @return array
     */
    protected function prepareStrategy($strategyEntity)
    {
        $strategy = [
            'id'        => $strategyEntity->getId(),
            'title'     => $strategyEntity->getTitle(),
            'createdAt' => $strategyEntity->getCreatedAt()->format(self::DATETIME_FORMAT),
            'updatedAt' => $strategyEntity->getUpdatedAt()->format(self::DATETIME_FORMAT),
            'items'     => []
        ];

        $strategyItemEntities = $strategyEntity->getItems();

        /** @var StrategyItemEntity $strategyItem */
        foreach ($strategyItemEntities as $strategyItemEntity) {
            $strategy['items'][] = $this->prepareStrategyItem($strategyItemEntity);
        }

        return $strategy;
    }

    /**
     * @param StrategyItemEntity $strategyItemEntity
     *
     * @return array
     */
    protected function prepareStrategyItem($strategyItemEntity)
    {
        return [
            'id'       => $strategyItemEntity->getId(),
            'settings' => $strategyItemEntity->getSettings(),
            'type'     => $strategyItemEntity->getType(),
            'sort'     => $strategyItemEntity->getSort()
        ];
    }

    protected function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }


    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }

    /**
     * @param $wordID
     *
     * @return WordEntity
     */
    protected function getWordById($wordID)
    {
        /** @var \Application\Service\Word $wordService  */
        $wordService = $this->getServiceLocator()->get('Application\Service\Word');
        return $wordService->getWordById($wordID, $this->getUser());
    }

    /**
     * @param $groupID
     *
     * @return WordsGroupEntity
     */
    protected function getGroupById($groupID)
    {
        /** @var \Application\Service\WordsGroup $wordService  */
        $wordService = $this->getServiceLocator()->get('Application\Service\WordsGroup');
        return $wordService->getWordsGroupById($groupID, $this->getUser());
    }

    /**
     * @param $strategyID
     *
     * @return StrategyEntity
     */
    protected function getStrategyById($strategyID)
    {
        /** @var \Application\Service\Strategy $strategyService  */
        $strategyService = $this->getServiceLocator()->get('Application\Service\Strategy');
        return $strategyService->getStrategyById($strategyID, $this->getUser());
    }

    /**
     * @param $downloadID
     *
     * @return DownloadEntity
     */
    protected function getDownloadById($downloadID)
    {
        /** @var \Application\Service\Download $downloadService  */
        $downloadService = $this->getServiceLocator()->get('Application\Service\Download');
        return $downloadService->getDownloadById($downloadID, $this->getUser());
    }

    /**
     * @throws \Api\Model\Exception
     * @return \Application\Model\Entity\User
     */
    protected function getUser()
    {
        /** @var \Application\Model\Entity\User $userEntity  */
        $authService = $this->serviceLocator->get('Zend\Authentication\AuthenticationService');
        if (!$authService->hasIdentity()) {
            throw new ApiException('Пользователь не авторизован');
        }
        $userEntity = $this->identity();
        return $userEntity;
    }
}

