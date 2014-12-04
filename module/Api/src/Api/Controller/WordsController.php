<?php

namespace Api\Controller;

use Zend\Http\Request;

use Application\Controller\AbstractActionController;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\Speaker as SpeakerEntity;
use Api\Model\Exception as ApiException;

class WordsController extends AbstractApiController
{
    const WORD_TYPE_SOURCE = 1;
    const WORD_TYPE_TARGET = 2;

    public function dropSeveralWordsAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            if (empty($data['wordsForDelete'])) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }
            $wordIDs = (array) $data['wordsForDelete'];
            if (empty($wordIDs)) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            /** @var \Application\Service\Word $wordService  */
            $wordService = $this->getServiceLocator()->get('Application\Service\Word');
            $wordService->dropSeveralWords($wordIDs, $this->getUser());

            return new JsonModel([
                'success' => true,
            ]);

        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }

    public function moveSeveralWordsAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            if (empty($data['wordsForMove'])) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }
            $wordIDs = (array) $data['wordsForMove'];
            $moveToGroup = intval($data['moveToGroup']);
            if (empty($wordIDs) || empty($moveToGroup)) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            $wordsGroupEntity = $this->getGroupById($moveToGroup);
            if (!$wordsGroupEntity) {
                throw new ApiException(null, ApiException::GROUP_NOT_FOUND);
            }

            /** @var \Application\Service\Word $wordService  */
            $wordService = $this->getServiceLocator()->get('Application\Service\Word');
            $wordService->moveSeveralWords($wordIDs, $wordsGroupEntity, $this->getUser());

            return new JsonModel([
                'success' => true,
            ]);

        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }

    public function addAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $wordForm = new \Api\Form\Word($entityManager);
            $wordEntity = new WordEntity();

            $wordForm->bind($wordEntity);
            $wordForm->setData($data);

            if ($wordForm->isValid()) {

                $entityManager->beginTransaction();

                /** @var WordEntity $newWordEntity */
                $newWordEntity = $wordForm->getData();
                $newWordEntity->setFkUser($this->getUser());

                /** @var \Application\Service\Word $wordService  */
                $wordService = $this->getServiceLocator()->get('Application\Service\Word');
                $newWordEntity = $wordService->save($newWordEntity);

                if ($newWordEntity) {

                    $word          = $wordEntity->getSource();
                    $language      = $wordEntity->getFkLanguageSource();
                    $speakerEntity = $wordService->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerSource($speakerEntity);

                    $word          = $wordEntity->getSourceSimple();
                    $language      = $wordEntity->getFkLanguageSource();
                    $speakerEntity = $wordService->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerSourceSimple($speakerEntity);

                    // ----
                    $word          = $wordEntity->getTarget();
                    $language      = $wordEntity->getFkLanguageTarget();
                    $speakerEntity = $wordService->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerTarget($speakerEntity);

                    $word          = $wordEntity->getTargetSimple();
                    $language      = $wordEntity->getFkLanguageTarget();
                    $speakerEntity = $wordService->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerTargetSimple($speakerEntity);

                    $wordService->save($newWordEntity);

                    $entityManager->commit();
                    return new JsonModel([
                        'word' => $this->prepareWord($newWordEntity),
                        'success' => true,
                    ]);
                } else {
                    $entityManager->rollback();
                    throw new ApiException(null, ApiException::COMMON_LOGICAL_ERROR);
                }
            } else {
                // 'error' => $wordForm->getMessages(),
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }
        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }

    public function updateAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            $wordID = intval($data['id']);
            if (!$wordID) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }
            $wordEntity = $this->getWordById($wordID);
            if (!$wordEntity) {
                throw new ApiException(null, ApiException::WORD_NOT_FOUND);
            }

            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $wordForm = new \Api\Form\Word($entityManager);
            $wordForm->bind($wordEntity);
            $wordForm->setData($data);

            if ($wordForm->isValid()) {

                $entityManager->beginTransaction();

                /** @var \Application\Service\Word $wordService  */
                $wordService = $this->getServiceLocator()->get('Application\Service\Word');

                if ($wordEntity->getSource() != $wordEntity->getFkSpeakerSource()->getWord()
                    || $wordEntity->getFkLanguageSource() != $wordEntity->getFkSpeakerTarget()->getFkLanguage()
                ) {
                    $word          = $wordEntity->getSource();
                    $language      = $wordEntity->getFkLanguageSource();
                    $speakerEntity = $wordService->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerSource($speakerEntity);
                }

                $sourceSimpleWord = $wordEntity->getFkSpeakerSourceSimple()
                    ? $wordEntity->getFkSpeakerSourceSimple()->getWord()
                    : '';
                $sourceSimpleWordLang = $wordEntity->getFkSpeakerSourceSimple()
                    ? $wordEntity->getFkSpeakerSourceSimple()->getFkLanguage()
                    : $wordEntity->getFkLanguageSource();

                if ($wordEntity->getSourceSimple() != $sourceSimpleWord
                    || $wordEntity->getFkLanguageSource() != $sourceSimpleWordLang
                ) {
                    $word          = $wordEntity->getSourceSimple();
                    $language      = $wordEntity->getFkLanguageSource();
                    $speakerEntity = $wordService->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerSourceSimple($speakerEntity);
                }

                if ($wordEntity->getTarget() != $wordEntity->getFkSpeakerTarget()->getWord()
                    || $wordEntity->getFkLanguageTarget() != $wordEntity->getFkSpeakerTarget()->getFkLanguage()
                ) {
                    $word          = $wordEntity->getTarget();
                    $language      = $wordEntity->getFkLanguageTarget();
                    $speakerEntity = $wordService->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerTarget($speakerEntity);
                }

                $targetSimpleWord = $wordEntity->getFkSpeakerTargetSimple()
                    ? $wordEntity->getFkSpeakerTargetSimple()->getWord()
                    : '';
                $targetSimpleWordLang = $wordEntity->getFkSpeakerTargetSimple()
                    ? $wordEntity->getFkSpeakerTargetSimple()->getFkLanguage()
                    : $wordEntity->getFkLanguageTarget();

                if ($wordEntity->getTargetSimple() != $targetSimpleWord
                    || $wordEntity->getFkLanguageTarget() != $targetSimpleWordLang
                ) {
                    $word          = $wordEntity->getTargetSimple();
                    $language      = $wordEntity->getFkLanguageTarget();
                    $speakerEntity = $wordService->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerTargetSimple($speakerEntity);
                }

                $result = $wordService->save($wordEntity);

                if ($result) {
                    $entityManager->commit();
                    return new JsonModel([
                        'word' => $this->prepareWord($wordEntity),
                        'success' => true,
                    ]);
                } else {
                    $entityManager->rollback();
                    throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
                }

            } else {
                throw new ApiException(null, ApiException::COMMON_LOGICAL_ERROR);
            }
        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }

    public function speakAction()
    {
        $wordID    = intval($this->params()->fromRoute('id'));
        $speakType = intval($this->params()->fromQuery('type'));

        if (!$wordID || !$speakType) {
            throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
        }

        $wordEntity = $this->getWordById($wordID);
        if (!$wordEntity) {
            throw new ApiException(null, ApiException::WORD_NOT_FOUND);
        }

        /** @var \Application\Service\Speaker $speakerService  */
        $speakerService = $this->getServiceLocator()->get('Application\Service\Speaker');

        $speakerEntity = $speakType == self::WORD_TYPE_SOURCE
            ? $wordEntity->getFkSpeakerSource()
            : $wordEntity->getFkSpeakerTarget();

        if (!$speakerEntity) {

            $word = $speakType == self::WORD_TYPE_SOURCE
                ? $wordEntity->getSource()
                : $wordEntity->getTarget();

            $language = $speakType == self::WORD_TYPE_SOURCE
                ? $wordEntity->getFkLanguageSource()
                : $wordEntity->getFkLanguageTarget();

            $speakerEntity = $speakerService->createSpeaker($word, $language);

            if ($speakType == self::WORD_TYPE_SOURCE) {
                $wordEntity->setFkSpeakerSource($speakerEntity);
            } else {
                $wordEntity->setFkSpeakerTarget($speakerEntity);
            }

            /** @var \Application\Service\Word $wordService  */
            $wordService = $this->getServiceLocator()->get('Application\Service\Word');
            $wordService->save($wordEntity);
        }

        if ($speakerEntity->getStatus() == SpeakerEntity::STATUS_IN_PROGRESS) {
            throw new ApiException(null, ApiException::WORD_SPEAKER_IN_PROGRESS);
        }

        $result = false;
        if ($speakerEntity->getStatus() == SpeakerEntity::STATUS_FRESH) {
            $result = $speakerService->createSound($speakerEntity);
        }

        /** @var \Application\Service\Store $storeService  */
        $storeService = $this->getServiceLocator()->get('Application\Service\Store');
        $speakURL = $storeService->getSpeakURL($speakerEntity->getId(), \Application\Service\Store::TYPE_SPEAKER);

        return new JsonModel([
            'success'  => $result,
            'speakURL' => $speakURL,
        ]);
    }
}