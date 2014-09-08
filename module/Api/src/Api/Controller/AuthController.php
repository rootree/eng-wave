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
use Application\Model\Entity\User as UserEntity;
use Application\Model\Entity\UserSettings as UserSettingsEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Api\Model\Exception as ApiException;
use Application\Model\Entity\Strategy as StrategyEntity;
use Application\Model\Entity\StrategyItem as StrategyItemEntity;

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

                $entityManager->beginTransaction();

                /** @var UserEntity $newUserEntity */
                $newUserEntity = $userForm->getData();
                $newUserEntity->setPassword(md5($newUserEntity->getPassword()));
                $newUserEntity->setCreatedAt(new \DateTime());
                $newUserEntity = $userService->save($newUserEntity);

                if ($newUserEntity) {

                    $wordsGroupEntity = new WordsGroupEntity();

                    $wordsGroupEntity->setTitle($data['initial']['group']);
                    $wordsGroupEntity->setCreatedAt(new \DateTime());
                    $wordsGroupEntity->setFkUser($newUserEntity);

                    /** @var \Application\Service\WordsGroup $wordsGroupService  */
                    $wordsGroupService = $this->getServiceLocator()->get('Application\Service\WordsGroup');
                    $wordsGroupEntity = $wordsGroupService->saveGroupEntity($wordsGroupEntity);

                    if ($wordsGroupEntity) {
                        $settings = $userService->settingsTemplate($wordsGroupEntity);
                        $settings = new UserSettingsEntity(ZendJson::encode($settings));
                        $newUserEntity->setSettings($settings);
                        $userService->save($newUserEntity);
                    }

                    /** @var StrategyEntity $strategyEntity */
                    $strategyEntity = new StrategyEntity();
                    $strategyEntity->setFkUser($newUserEntity);
                    $strategyEntity->setCreatedAt(new \DateTime());
                    $strategyEntity->setTitle($data['initial']['strategy']);

                    /** @var \Application\Service\Strategy $strategyService  */
                    $strategyService = $this->getServiceLocator()->get('Application\Service\Strategy');
                    $strategyEntity = $strategyService->save($strategyEntity);

                    if ($strategyEntity) {
                        $items = [
                            $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SOURCE),
                            $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SILENCE),
                            $strategyService->getItemTemplate(StrategyItemEntity::TYPE_TARGET),
                            $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SOURCE),
                            $strategyService->getItemTemplate(StrategyItemEntity::TYPE_TARGET)
                        ];
                        $strategyService->bindStrategyItems($strategyEntity, $newUserEntity, $items);
                        $entityManager->refresh($strategyEntity);
                    }

                    $entityManager->commit();

                    return $this->auth($newUserEntity->getEmail(), $userForm->get('password')->getValue());

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
