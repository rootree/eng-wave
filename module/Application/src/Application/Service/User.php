<?php

namespace Application\Service;

use Application\Model\Repository\User as UserRepository;
use Application\Model\Entity\User as UserEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Doctrine\ORM\EntityManager;
use Application\Model\Entity\Strategy as StrategyEntity;
use Application\Model\Entity\StrategyItem as StrategyItemEntity;
use Application\Model\Entity\StrategyItemSettings\Silent as StrategyItemSilentEntity;
use Application\Model\Entity\UserSettings as UserSettingsEntity;
use Application\Service\WordsGroup as WordsGroupService;
use Application\Service\Strategy as StrategyService;
use Application\Service\Package as PackageService;
use Zend\Db\TableGateway\Exception\RuntimeException;
use Zend\Json\Json as ZendJson;

class User
{
    const DEMO_USER_NAME = 'Demo';
    const DEMO_USER_PASSWORD = 'demo_password';
    const DEMO_USER_EMAIL = 'demo@engwave.com';
    const DEMO_USER_INIT_GROUP = 'Demo group';
    const DEMO_USER_INIT_STRATEGY = 'Demo strategy';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var StrategyService
     */
    private $strategyService;

    /**
     * @var WordsGroupService
     */
    private $wordsGroupService;

    /**
     * @var PackageService
     */
    private $packageGroupService;

    public function __construct(
        EntityManager $entityManager,
        WordsGroupService $wordsGroupService,
        StrategyService $strategyService,
        PackageService $packageGroupService
    )
    {
        $this->entityManager = $entityManager;
        $this->wordsGroupService = $wordsGroupService;
        $this->strategyService = $strategyService;
        $this->packageGroupService = $packageGroupService;
        $this->userRepository = $entityManager->getRepository('Application\Model\Entity\User');
    }

    /**
     * @param UserEntity $userEntity
     *
     * @return UserEntity
     */
    public function save($userEntity)
    {
        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
        return $userEntity;
    }

    public function resetDemoUser()
    {
        $demoUserEntity = $this->getUserByEmail(self::DEMO_USER_EMAIL);
        if ($demoUserEntity !== null) {
            $demoUserId = $demoUserEntity->getId();
            $sql = <<< SQL
DELETE FROM `download` WHERE `fk_user` = $demoUserId;
DELETE FROM `feedback` WHERE `fk_user` = $demoUserId;
DELETE FROM `package__user` WHERE `fk_user` = $demoUserId;
DELETE FROM `strategy_item` WHERE `fk_user` = $demoUserId;
DELETE FROM `strategy` WHERE `fk_user` = $demoUserId;
DELETE FROM `word` WHERE `fk_user` = $demoUserId;
DELETE FROM `words_group` WHERE `fk_user` = $demoUserId;
SQL;
            $this->entityManager->getConnection()->exec( $sql );
            $this->entityManager->remove($demoUserEntity);
            $this->entityManager->flush();
        }

        $userEntity = new UserEntity();
        $userEntity->setEmail(self::DEMO_USER_EMAIL);
        $userEntity->setName(self::DEMO_USER_NAME);
        $userEntity->setPassword(self::DEMO_USER_PASSWORD);

        $demoUserEntity = $this->createUser($userEntity, self::DEMO_USER_INIT_GROUP, self::DEMO_USER_INIT_STRATEGY);

        $packageEntities = $this->packageGroupService->getDefaultPackages();
        if ($packageEntities) {
            $this->packageGroupService->install($packageEntities[0], $demoUserEntity);
        }
    }

    /**
     * @param $initialWordsGroupEntity
     *
     * @return UserEntity
     */
    public function settingsTemplate($initialWordsGroupEntity)
    {
        $settings = new \stdClass();
        $settings->currentGroup = $initialWordsGroupEntity->getId();
        return $settings;
    }

    /**
     * @param $email
     * @return UserEntity
     *
     */
    public function getUserByEmail($email)
    {
        return $this->userRepository->findOneBy([
            'email' => $email,
        ]);
    }

    /**
     * @param $password
     *
     * @return UserEntity
     */
    public function generatePassword($password)
    {
        return md5($password);
    }

    /**
     * @param UserEntity $newUserEntity
     * @param string $defaultGroupName
     * @param string $defaultStrategyName
     * @return UserEntity
     * @throws \RuntimeException
     *
     */
    public function createUser($newUserEntity, $defaultGroupName, $defaultStrategyName)
    {
        $this->entityManager->beginTransaction();

        $newUserEntity->setPassword($this->generatePassword($newUserEntity->getPassword()));
        $newUserEntity->setCreatedAt(new \DateTime());
        $newUserEntity = $this->save($newUserEntity);

        if ($newUserEntity) {

            $wordsGroupEntity = new WordsGroupEntity();

            $wordsGroupEntity->setTitle($defaultGroupName);
            $wordsGroupEntity->setCreatedAt(new \DateTime());
            $wordsGroupEntity->setFkUser($newUserEntity);

            $wordsGroupEntity = $this->wordsGroupService->saveGroupEntity($wordsGroupEntity);

            if ($wordsGroupEntity) {
                $settings = $this->settingsTemplate($wordsGroupEntity);
                $settings = new UserSettingsEntity(ZendJson::encode($settings));
                $newUserEntity->setSettings($settings);
                $this->save($newUserEntity);
            }

            /** @var StrategyEntity $strategyEntity */
            $strategyEntity = new StrategyEntity();
            $strategyEntity->setFkUser($newUserEntity);
            $strategyEntity->setCreatedAt(new \DateTime());
            $strategyEntity->setTitle($defaultStrategyName);

            /** @var \Application\Service\Strategy $strategyService  */
            $strategyService = $this->strategyService;
            $strategyEntity = $strategyService->save($strategyEntity);

            if ($strategyEntity) {
                $items = [
                    $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SOURCE),
                    $strategyService->getItemTemplate(
                        StrategyItemEntity::TYPE_SILENCE,
                        array(
                            'type' => StrategyItemSilentEntity::TYPE_SOURCE, // Тишина на время оригинала
                            'seconds' => 0, // Тишина на время оригинала
                        )
                    ),
                    $strategyService->getItemTemplate(StrategyItemEntity::TYPE_TARGET),
                    $strategyService->getItemTemplate(StrategyItemEntity::TYPE_SOURCE),
                    $strategyService->getItemTemplate(StrategyItemEntity::TYPE_TARGET)
                ];
                $strategyService->bindStrategyItems($strategyEntity, $newUserEntity, $items);
                $this->entityManager->refresh($strategyEntity);
            }

            $this->entityManager->commit();

            return $newUserEntity;

        } else {

            $this->entityManager->rollback();
            throw new \RuntimeException('Can\'t create a user');

        }
    }
}
