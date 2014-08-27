<?php

namespace Api\Controller;

use Zend\Http\Request;

use Application\Controller\AbstractActionController;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Application\Model\Entity\Language as LanguageEntity;
use Api\Model\Exception as ApiException;

class GroupsController extends AbstractApiController
{

    public function contentAction()
    {
        $groupID = intval($this->params()->fromRoute('id'));
        if (!$groupID) {
            throw new ApiException('Номер группы слов не был получен');
        }

        $currentGroupEntity = $this->getGroupById($groupID);
        if (!$currentGroupEntity) {
            throw new ApiException('Запрощенная группа слов не найдена.');
        }

        $userEntity = $this->getUser();
        if ($groupID != $userEntity->getSettings()->getCurrentGroup()) {
            $userEntity->getSettings()->setCurrentGroup($groupID);
            $userEntity->setSettings($userEntity->getSettings());
            $this->getEntityManager()->flush();
        }

        /** @var \Application\Service\Word $wordService  */
        $wordService = $this->getServiceLocator()->get('Application\Service\Word');
        $wordsEntities = $wordService->getAllWords($currentGroupEntity, $userEntity);

        return new JsonModel([
            'words' => $this->prepareWords($wordsEntities),
        ]);
    }

    public function dropAction()
    {
        $groupID = intval($this->params()->fromRoute('id'));
        if (!$groupID) {
            throw new ApiException('Группа не найдено');
        }

        $groupEntity = $this->getGroupById($groupID);
        if (!$groupEntity) {
            throw new ApiException('Группа не найдено #2');
        }

        $countOfWordsInGroup = $groupEntity->getWordList()->count();
        if ($countOfWordsInGroup) {
            throw new ApiException(sprintf('Данная группа, содержит слова (%d), которые будут потеряны. Откройте удаляемую группу, выделите все слова, переместите их в другую группу или удалите, когда группа для удаления будет пуста, вы сможете удалить ее.', $countOfWordsInGroup));
        }

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $entityManager->beginTransaction();

        /** @var \Application\Service\WordsGroup $wordsGroupService  */
        $wordsGroupService = $this->getServiceLocator()->get('Application\Service\WordsGroup');
        $wordsGroupService->dropGroup($groupEntity);

        $userEntity = $this->getUser();
        if ($userEntity->getSettings()->getCurrentGroup() == $groupID) {

            /** @var \Application\Service\WordsGroup $wordsGroupService  */
            $wordsGroupService = $this->getServiceLocator()->get('Application\Service\WordsGroup');
            $groupEntities = $wordsGroupService->getAllGroups($userEntity);

            if (count($groupEntities) == 0) {
                $entityManager->rollback();
                throw new ApiException('Последняя группа удалена не может быть.');
            }

            foreach ($groupEntities as $group2Entity) {
                break;
            }
            $userEntity->getSettings()->setCurrentGroup($group2Entity->getId());
            $userEntity->setSettings($userEntity->getSettings());
            $this->getEntityManager()->flush();
        }

        $entityManager->commit();

        return new JsonModel([
            'success' => true,
            'currentGroup' => $userEntity->getSettings()->getCurrentGroup(),
        ]);
    }

    public function updateAction()
    {
        $groupID = intval($this->params()->fromRoute('id'));
        if (!$groupID) {
            throw new ApiException('Группа не найдено');
        }
        $wordsGroupEntity = $this->getGroupById($groupID);
        if (!$wordsGroupEntity) {
            throw new ApiException('Группа не найдено #1');
        }

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException('Параметры для добавления загрузки не найдены');
            }

            if (empty($data['title'])) {
                throw new ApiException('Параметры для добавления нового слова не найдены');
            }

            $wordsGroupEntity->setTitle($data['title']);

            /** @var \Application\Service\WordsGroup $wordsGroupService  */
            $wordsGroupService = $this->getServiceLocator()->get('Application\Service\WordsGroup');
            $wordsGroupService->saveGroupEntity($wordsGroupEntity);

            return new JsonModel([
                'success' => true,
            ]);

        } else {
            throw new ApiException('Параметры для добавления нового слова не найдены');
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

            $wordsGroupForm = new \Api\Form\WordsGroup($entityManager);
            $wordsGroupEntity = new WordsGroupEntity();

            $wordsGroupForm->bind($wordsGroupEntity);
            $wordsGroupForm->setData($data);

            if ($wordsGroupForm->isValid()) {

                $entityManager->beginTransaction();

                /** @var WordsGroupEntity $newWordsGroupEntity */
                $newWordsGroupEntity = $wordsGroupForm->getData();
                $newWordsGroupEntity->setFkUser($this->getUser());

                /** @var \Application\Service\WordsGroup $wordsGroupService  */
                $wordsGroupService = $this->getServiceLocator()->get('Application\Service\WordsGroup');
                $newWordsGroupEntity = $wordsGroupService->saveGroupEntity($newWordsGroupEntity);

                if ($newWordsGroupEntity) {
                    $entityManager->commit();
                    return new JsonModel([
                        'group' => $this->prepareGroup($newWordsGroupEntity),
                        'success' => true,
                    ]);
                } else {
                    $entityManager->rollback();
                    throw new ApiException('Произошла логическая ошибка');
                }
            } else {
                // 'error' => $wordsGroupForm->getMessages(),
                throw new ApiException('Заполните все обязательные поля');
            }
        } else {
            throw new ApiException('Параметры для добавления нового слова не найдены');
        }
    }
}