<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api\Controller;

use Application\Controller\AbstractActionController,
        Zend\Authentication\Adapter\DbTable,
        Zend\Session\Container as SessionContainer,
        Zend\View\Model\JsonModel,
        Api\Form\Login;
use Zend\Json\Json as ZendJson;
use Api\Model\Exception as ApiException;

class AbstractAuthController extends AbstractApiController
{
    protected function auth($email, $password)
    {
        // If you used another name for the authentication service, change it here
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $adapter = $authService->getAdapter();

        $adapter->setIdentityValue($email);
        $adapter->setCredentialValue($password);

        /** @var \Zend\Authentication\Result $authResult  */
        $authResult = $authService->authenticate();

        if ($authResult->isValid()) {
            $identity = $authResult->getIdentity();
            $authService->getStorage()->write($identity);
            return new JsonModel(array(
                'userSettings' => $this->getUserJSON(),
                'CSRF'         => $this->getCsrfHash(),
            ));
        } else {
            // $authResult->getMessages()
            throw new ApiException(null, ApiException::AUTH_FAILED);
        }
    }
}
