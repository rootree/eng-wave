<?php

namespace Api\Controller;

use Zend\Http\Request;

use Application\Controller\AbstractActionController;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;
use Application\Model\Entity\Word as WordEntity;
use Application\Model\Entity\Feedback as FeedbackEntity;
use Api\Model\Exception as ApiException;

class FeedbackController extends AbstractApiController
{
    public function addAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException('Параметры обратной связи не найдены');
            }

            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $feedbackForm = new \Api\Form\Feedback($entityManager);
            $feedbackEntity = new FeedbackEntity();

            $feedbackForm->bind($feedbackEntity);
            $feedbackForm->setData($data);

            if ($feedbackForm->isValid()) {

                /** @var FeedbackEntity $newFeedbackEntity */
                $newFeedbackEntity = $feedbackForm->getData();
                $newFeedbackEntity->setFkUser($this->identity());
                $newFeedbackEntity->setCreatedAt(new \DateTime());

                /** @var \Application\Service\Feedback $feedbackService  */
                $feedbackService = $this->getServiceLocator()->get('Application\Service\Feedback');
                $newFeedbackEntity = $feedbackService->save($newFeedbackEntity);

                if ($newFeedbackEntity) {
                    return new JsonModel([
                        'success' => true,
                    ]);
                } else {
                    throw new ApiException('Произошла логическая ошибка');
                }
            } else {
                // 'error' => $feedbackForm->getMessages(),
                throw new ApiException('Заполните все обязательные поля');
            }
        } else {
            throw new ApiException('Параметры для добавления нового запроса не найдены');
        }
    }
}