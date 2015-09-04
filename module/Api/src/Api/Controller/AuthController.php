<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api\Controller;

use Zend\Authentication\Adapter\DbTable,
        Zend\Session\Container as SessionContainer,
        Zend\View\Model\JsonModel,
        Api\Form\Login;
use Application\Model\Entity\User as UserEntity;
use Api\Model\Exception as ApiException;

class AuthController extends AbstractAuthController
{
    public function loginAction()
    {
        $authService = $this->serviceLocator->get('Zend\Authentication\AuthenticationService');
        if ($authService->hasIdentity()) {
            return new JsonModel(array(
                'userSettings' => $this->getUserJSON(),
                'CSRF'         => $this->getCsrfHash(),
            ));
        }

        $loginForm = new Login();

        /** @var \Zend\Stdlib\RequestInterface $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            $loginForm->setData($data);
            if (!$loginForm->isValid()) {
                throw new ApiException(null, ApiException::AUTH_FAILED);
            }
            $loginData  = $loginForm->getData();

            return $this->auth($loginData['email'], $loginData['password']);

        } else {
            throw new ApiException(null, ApiException::COMMON_EMPTY_REQUEST);
        }
    }

    public function logoutAction()
    {
        /** @var \Zend\Authentication\AuthenticationService $authService  */
        $authService = $this->serviceLocator->get('Zend\Authentication\AuthenticationService');
        $authService->clearIdentity();
        return new JsonModel(array());
    }


    public function signUpAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getPostParams($request);
            if (!$data) {
                throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
            }

            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $userForm = new \Api\Form\SignUp($entityManager);
            $userEntity = new UserEntity();

            $userForm->bind($userEntity);
            $userForm->setData($data);

            if ($userForm->isValid()) {

                if (
                    empty($data['initial']['group']) ||
                    empty($data['initial']['strategy'])
                ) {
                    throw new ApiException(null, ApiException::COMMON_INCORRECT_ARGUMENT);
                }

                /** @var \Application\Service\User $userService  */
                $userService = $this->getServiceLocator()->get('Application\Service\User');
                $emailUserEntity = $userService->getUserByEmail($userEntity->getEmail());
                if ($emailUserEntity) {
                    throw new ApiException(null, ApiException::AUTH_EMAIL_EXISTS);
                }

                try {
                    $newUserEntity = $userService->createUser(
                        $userForm->getData(),
                        $data['initial']['group'],
                        $data['initial']['strategy']
                    );
                    return $this->auth($newUserEntity->getEmail(), $userForm->get('password')->getValue());
                } catch (\RuntimeException $e) {
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
