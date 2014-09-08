<?php

namespace Api\Controller;

use Zend\Http\Request;

use Application\Controller\AbstractActionController;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;
use Application\Model\Entity\Strategy as StrategyEntity;
use Api\Model\Exception as ApiException;

class StrategiesController extends AbstractApiController
{
    public function getAllAction()
    {
        $userEntity = $this->getUser();

        /** @var \Application\Service\Strategy $strategyService  */
        $strategyService = $this->getServiceLocator()->get('Application\Service\Strategy');
        $strategyEntities = $strategyService->getAllStrategies($userEntity);

        $strategiesForOriginal = $strategiesForTranslate = array();

        foreach ($strategyEntities as $strategyEntity) {
            if ($strategyEntity->getType() == StrategyEntity::TYPE_SOURCE) {
                $strategiesForOriginal[] = $this->prepareStrategy($strategyEntity);
            } else {
                $strategiesForTranslate[] = $this->prepareStrategy($strategyEntity);
            }
        }

        return new JsonModel([
            'originals' => $strategiesForOriginal,
            'translate' => $strategiesForTranslate,
        ]);
    }

    public function dropAction()
    {
        $strategyID = intval($this->params()->fromRoute('id'));
        if (!$strategyID) {
            throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
        }

        /** @var \Application\Service\Strategy $strategyService  */
        $strategyService = $this->getServiceLocator()->get('Application\Service\Strategy');

        $currentStrategyEntity = $this->getStrategyById($strategyID);
        if (!$currentStrategyEntity) {
            throw new ApiException(null, ApiException::STRATEGY_NOT_FOUND);
        }

        $strategyService->dropStrategy($currentStrategyEntity);

        return new JsonModel([
            'success' => true,
        ]);
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

            $strategyForm = new \Api\Form\Strategy($entityManager);
            $strategyEntity = new StrategyEntity();

            $strategyForm->bind($strategyEntity);
            $strategyForm->setData($data);

            if (empty($data['items'])) {
                throw new ApiException(null, ApiException::STRATEGY_EMPTY_ITEMS);
            }

            if ($strategyForm->isValid()) {

                $entityManager->beginTransaction();

                /** @var StrategyEntity $newStrategyEntity */
                $newStrategyEntity = $strategyForm->getData();
                $newStrategyEntity->setFkUser($this->getUser());
                $newStrategyEntity->setCreatedAt(new \DateTime());

                /** @var \Application\Service\Strategy $strategyService  */
                $strategyService = $this->getServiceLocator()->get('Application\Service\Strategy');
                $newStrategyEntity = $strategyService->save($newStrategyEntity);

                if ($newStrategyEntity) {

                    $strategyService->bindStrategyItems($newStrategyEntity, $this->getUser(), $data['items']);

                    $entityManager->commit();
                    $entityManager->refresh($newStrategyEntity);

                    return new JsonModel([
                        'strategy' => $this->prepareStrategy($newStrategyEntity),
                        'success' => true,
                    ]);
                } else {
                    $entityManager->rollback();
                    throw new ApiException(null, ApiException::COMMON_LOGICAL_ERROR);
                }
            } else {
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
                throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
            }

            $strategyID = intval($data['id']);
            if (!$strategyID) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            $strategyEntity = $this->getStrategyById($strategyID);
            if (!$strategyEntity) {
                throw new ApiException(null, ApiException::STRATEGY_NOT_FOUND);
            }

            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $strategyForm = new \Api\Form\Strategy($entityManager);
            $strategyForm->bind($strategyEntity);
            $strategyForm->setData($data);

            if ($strategyForm->isValid()) {

                $entityManager->beginTransaction();

                /** @var \Application\Service\Strategy $strategyService  */
                $strategyService = $this->getServiceLocator()->get('Application\Service\Strategy');

                $result = $strategyService->save($strategyEntity);

                if ($result) {

                    $strategyService->eraseStrategyItems($strategyEntity);
                    $strategyService->bindStrategyItems($strategyEntity, $this->getUser(), $data['items']);

                    $entityManager->commit();
                    return new JsonModel([
                        // 'word' => $this->prepareWord($wordEntity),
                        'success' => true,
                    ]);
                } else {
                    $entityManager->rollback();
                    throw new ApiException(null, ApiException::COMMON_LOGICAL_ERROR);
                }

            } else {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }
        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }
}