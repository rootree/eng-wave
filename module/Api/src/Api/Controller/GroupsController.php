<?php

namespace Api\Controller;

use Zend\Http\Request;

use Application\Controller\AbstractActionController;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Api\Model\Exception as ApiException;

class GroupsController extends AbstractApiController
{

    public function contentAction()
    {
        $groupID = intval($this->params()->fromRoute('id'));
        if (!$groupID) {
            throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
        }

        $currentGroupEntity = $this->getGroupById($groupID);
        if (!$currentGroupEntity) {
            throw new ApiException(null, ApiException::GROUP_NOT_FOUND);
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
            throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
        }

        $groupEntity = $this->getGroupById($groupID);
        if (!$groupEntity) {
            throw new ApiException(null, ApiException::GROUP_NOT_FOUND);
        }

        $countOfWordsInGroup = $groupEntity->getWordList()->count();
        if ($countOfWordsInGroup) {
            throw new ApiException(null, ApiException::GROUP_WORDS_EXIST);
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
                throw new ApiException(null, ApiException::GROUP_LAST);
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
            throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
        }
        $wordsGroupEntity = $this->getGroupById($groupID);
        if (!$wordsGroupEntity) {
            throw new ApiException(null, ApiException::GROUP_NOT_FOUND);
        }

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            if (empty($data['title'])) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            $wordsGroupEntity->setTitle($data['title']);

            /** @var \Application\Service\WordsGroup $wordsGroupService  */
            $wordsGroupService = $this->getServiceLocator()->get('Application\Service\WordsGroup');
            $wordsGroupService->saveGroupEntity($wordsGroupEntity);

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
                    throw new ApiException(null, ApiException::COMMON_LOGICAL_ERROR);
                }
            } else {
                // 'error' => $wordsGroupForm->getMessages(),
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }
        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }
}