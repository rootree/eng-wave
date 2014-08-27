<?php

namespace Api\Controller;

use Zend\Http\Request;

use Application\Controller\AbstractActionController;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Application\Model\Entity\Language as LanguageEntity;
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
                throw new ApiException('Параметры для добавления загрузки не найдены');
            }

            if (empty($data['wordsForDelete'])) {
                throw new ApiException('Параметры для добавления удаления не найдены');
            }
            $wordIDs = (array) $data['wordsForDelete'];
            if (empty($wordIDs)) {
                throw new ApiException('Параметры для добавления удаления не найдены #1');
            }

            /** @var \Application\Service\Word $wordService  */
            $wordService = $this->getServiceLocator()->get('Application\Service\Word');
            $wordService->dropSeveralWords($wordIDs, $this->getUser());

            return new JsonModel([
                'success' => true,
            ]);

        } else {
            throw new ApiException('Параметры для добавления нового слова не найдены');
        }
    }

    public function moveSeveralWordsAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException('Параметры для добавления загрузки не найдены');
            }

            if (empty($data['wordsForMove'])) {
                throw new ApiException('Параметры для перемещения не найдены');
            }
            $wordIDs = (array) $data['wordsForMove'];
            $moveToGroup = intval($data['moveToGroup']);
            if (empty($wordIDs) || empty($moveToGroup)) {
                throw new ApiException('Параметры для перемещения не найдены #1');
            }

            $wordsGroupEntity = $this->getGroupById($moveToGroup);
            if (!$wordsGroupEntity) {
                throw new ApiException('Группа не найдено #1');
            }

            /** @var \Application\Service\Word $wordService  */
            $wordService = $this->getServiceLocator()->get('Application\Service\Word');
            $wordService->moveSeveralWords($wordIDs, $wordsGroupEntity, $this->getUser());

            return new JsonModel([
                'success' => true,
            ]);

        } else {
            throw new ApiException('Параметры для перемещения не найдены #2');
        }
    }

    public function addAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException('Параметры для добавления загрузки не найдены');
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

                    /** @var \Application\Service\Speaker $speakerService  */
                    $speakerService = $this->getServiceLocator()->get('Application\Service\Speaker');

                    $word          = $wordEntity->getSource();
                    $language      = $wordEntity->getFkLanguageSource();
                    $speakerEntity = $this->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerSource($speakerEntity);

                    $word          = $wordEntity->getTarget();
                    $language      = $wordEntity->getFkLanguageTarget();
                    $speakerEntity = $this->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerTarget($speakerEntity);
                    $wordService->save($newWordEntity);

                    $entityManager->commit();
                    return new JsonModel([
                        'word' => $this->prepareWord($newWordEntity),
                        'success' => true,
                    ]);
                } else {
                    throw new ApiException('Произошла логическая ошибка');
                }
            } else {
                // 'error' => $wordForm->getMessages(),
                throw new ApiException('Заполните все обязательные поля');
            }
        } else {
            throw new ApiException('Параметры для добавления нового слова не найдены');
        }
    }

    private function getSpeaker($word, $language)
    {
        /** @var \Application\Service\Speaker $speakerService  */
        $speakerService = $this->getServiceLocator()->get('Application\Service\Speaker');

        $speakerEntity = $speakerService->createSpeaker($word, $language);
        return $speakerEntity;
    }

    public function updateAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException('Параметры для добавления загрузки не найдены');
            }

            $wordID = intval($data['id']);
            if (!$wordID) {
                throw new ApiException('Слово не найдено');
            }
            $wordEntity = $this->getWordById($wordID);
            if (!$wordEntity) {
                throw new ApiException('Слово не найдено #2');
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
                    $speakerEntity = $this->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerSource($speakerEntity);
                }

                if ($wordEntity->getTarget() != $wordEntity->getFkSpeakerTarget()->getWord()
                    || $wordEntity->getFkLanguageTarget() != $wordEntity->getFkSpeakerTarget()->getFkLanguage()
                ) {
                    $word          = $wordEntity->getTarget();
                    $language      = $wordEntity->getFkLanguageTarget();
                    $speakerEntity = $this->getSpeaker($word, $language);

                    $wordEntity->setFkSpeakerTarget($speakerEntity);
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
                    throw new ApiException('Произошла логическая ошибка');
                }

            } else {
                throw new ApiException('Заполните все обязательные поля');
            }
        } else {
            throw new ApiException('Параметры для обновления слова не найдены');
        }
    }

    public function speakAction()
    {
        $wordID    = intval($this->params()->fromRoute('id'));
        $speakType = intval($this->params()->fromQuery('type'));

        if (!$wordID || !$speakType) {
            throw new ApiException('Не получены пораметры');
        }

        $wordEntity = $this->getWordById($wordID);
        if (!$wordEntity) {
            throw new ApiException('Слово не найдено #2');
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
            throw new ApiException('Неловкий момент. Фаил подготавливается для воспроизведения. Попробуйте через пару секунд.');
        }

        if ($speakerEntity->getStatus() == SpeakerEntity::STATUS_FRESH) {
            $speakerService->createSound($speakerEntity);
        }

        /** @var \Application\Service\Store $storeService  */
        $storeService = $this->getServiceLocator()->get('Application\Service\Store');
        $speakURL = $storeService->getSpeakURL($speakerEntity->getId(), \Application\Service\Store::TYPE_SPEAKER);

        return new JsonModel([
            'success'  => true,
            'speakURL' => $speakURL,
        ]);
    }
}