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
        Zend\Session\Container as SessionContainer ;
use Zend\Json\Json as ZendJson;
use Application\Model\Entity\User as UserEntity;
use Application\Model\Entity\Package as PackageEntity;
use Application\Model\Entity\UserSettings as UserSettingsEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Api\Model\Exception as ApiException;
use Application\Model\Entity\Strategy as StrategyEntity;
use Application\Model\Entity\StrategyItem as StrategyItemEntity;
use Zend\Json\Json, Zend\View\Model\JsonModel;
use Application\Model\Entity\StrategyItemSettings\Silent as SilentSettings;

class UserController extends AbstractAuthController
{
    public function createUserAction()
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
                $newUserEntity->setPassword($userService->generatePassword($userForm->get('password')->getValue()));
                $newUserEntity->setCreatedAt(new \DateTime());
                $newUserEntity = $userService->save($newUserEntity);

                if ($newUserEntity) {

                    $this->baseEnvironment($newUserEntity, $data['initial']['group'], $data['initial']['strategy']);

                    $entityManager->commit();

                    $this->installBasePackages($newUserEntity);

                    $entityManager->refresh($newUserEntity);

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

    /**
     * @param UserEntity $userEntity
     *
     * @return PackageEntity
     */
    protected function installBasePackages($userEntity)
    {
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        /** @var \Application\Service\Package $packageService  */
        $packageService = $this->getServiceLocator()->get('Application\Service\Package');
        $packageEntities = $packageService->getDefaultPackages();

        /** @var PackageEntity $packageEntity */
        foreach ($packageEntities as $packageEntity) {
            $wordsGroupEntity = $packageService->install($packageEntity, $userEntity);
            $entityManager->refresh($wordsGroupEntity);
        }
    }

    /**
     * @param UserEntity $newUserEntity
     * @param $groupTitle
     * @param $strategyTitle
     */
    protected function baseEnvironment($newUserEntity, $groupTitle, $strategyTitle)
    {
        /** @var \Application\Service\User $userService  */
        $userService = $this->getServiceLocator()->get('Application\Service\User');
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $wordsGroupEntity = new WordsGroupEntity();

        $wordsGroupEntity->setTitle($groupTitle);
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
        $strategyEntity->setTitle($strategyTitle);

        /** @var \Application\Service\Strategy $strategyService  */
        $strategyService = $this->getServiceLocator()->get('Application\Service\Strategy');
        $strategyEntity = $strategyService->save($strategyEntity);

        if ($strategyEntity) {

            // Оригинал Тишина на время оригинала Перевод Оригинал Тишина на время оригинала Перевод
            // Тишина на время оригинала Перевод Тишина на время оригинала Перевод Тишина на время оригинала

            $items   = [];
            $items[] = $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SOURCE);
            $items[] = $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SILENCE, [
                'type'    => SilentSettings::TYPE_SOURCE,
                'seconds' => 0,
            ]);
            $items[] = $strategyService->getItemTemplate(StrategyItemEntity::TYPE_TARGET);
            $items[] = $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SOURCE);
            $items[] = $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SILENCE, [
                'type'    => SilentSettings::TYPE_SOURCE,
                'seconds' => 0,
            ]);
            $items[] = $strategyService->getItemTemplate(StrategyItemEntity::TYPE_TARGET);
            $items[] = $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SILENCE, [
                'type'    => SilentSettings::TYPE_SOURCE,
                'seconds' => 0,
            ]);
            $items[] = $strategyService->getItemTemplate(StrategyItemEntity::TYPE_TARGET);
            $items[] = $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SILENCE, [
                'type'    => SilentSettings::TYPE_SOURCE,
                'seconds' => 0,
            ]);

            $strategyService->bindStrategyItems($strategyEntity, $newUserEntity, $items);
            $entityManager->refresh($strategyEntity);
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

            /** @var \Application\Model\Entity\User $userEntity  */
            $userEntity = $this->getUser();

            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $wordForm = new \Api\Form\UserUpdate($entityManager);
            $wordForm->bind($userEntity);
            $wordForm->setData($data);

            if ($wordForm->isValid()) {

                $entityManager->beginTransaction();

                /** @var \Application\Service\User $userService  */
                $userService = $this->getServiceLocator()->get('Application\Service\User');
                $password = $wordForm->get('password')->getValue();
                if (!empty($password)) {
                    $userEntity->setPassword($userService->generatePassword($wordForm->get('password')->getValue()));
                }

                $result = $userService->save($userEntity);

                if ($result) {
                    $entityManager->commit();
                    return new JsonModel([
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
