<?php

namespace Api\Controller;

use Zend\Http\Request;

use Application\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Exception\DomainException;
use Api\Model\Exception as ApiException;
use Zend\View\Model\JsonModel, Zend\Json\Json as ZendJson;

class AbstractApiController extends AbstractActionController
{
    private $controllersWithoutAuth = [
        'Api\\Controller\\Auth',
        'Api\\Controller\\User',
        'Api\\Controller\\Feedback',
    ];

    /**
     * @inheritDoc
     */
    public function onDispatch(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        if (!$routeMatch) {
            throw new DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $method = static::getMethodFromAction($action);

        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }

        $currentController = $routeMatch->getParam('controller');
        $authToken = $this->params()->fromQuery('_auth_token');
        if (
            //(empty($authToken) || $this->getCsrfHash() != $authToken) &&
            (empty($authToken)) &&
            !in_array($currentController, $this->controllersWithoutAuth)
        ) {
            $method = 'authRequiredAction';
        }

        try {
            $actionResponse = $this->$method();
        } catch (ApiException $e) {
            $this->getResponse()->setStatusCode(400);
            $actionResponse = new JsonModel([
                'success' => false,
                'code'    => $e->getCode()
            ]);
        } catch (\Exception $e) {

            $sm = $event->getApplication()->getServiceManager();
            $service = $sm->get('ApplicationServiceErrorHandling');
            $service->logException($e);

            $this->getResponse()->setStatusCode(500);
            $actionResponse = new JsonModel([
                'success' => false,
                'code'    => ApiException::COMMON_INTERNAL_ERROR
            ]);
        }

        $event->setResult($actionResponse);

        return $actionResponse;
    }

    public function notFoundAction()
    {
        $this->getResponse()->setStatusCode(404);
        return new JsonModel([
            'success' => false,
            'code'    => ApiException::COMMON_ROUTE_NOT_FOUND
        ]);
    }

    public function authRequiredAction()
    {
        $this->getResponse()->setStatusCode(401);
        return new JsonModel([
            'success' => false,
            'code'    => ApiException::COMMON_SESSION_EXPIRED
        ]);
    }


    /**
     * @param \Zend\Http\Request $request
     *
     * @return array
     */
    protected function getPostParams($request)
    {
        $body = $request->getContent();
        return ZendJson::decode($body, ZendJson::TYPE_ARRAY);
    }

}