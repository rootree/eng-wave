<?php

namespace Application\Service;

use Application\Model\Repository\Strategy as StrategyRepository;
use Application\Model\Entity\Strategy as StrategyEntity;
use Application\Model\Entity\StrategyItem as StrategyItemEntity;
use Application\Model\Entity\StrategyItemSettings\Silent as SilentSettings;
use Application\Model\Entity\StrategyItemSettings\Factory as FactorySettings;
use Doctrine\ORM\EntityManager;

class Strategy
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var StrategyRepository
     */
    private $strategyRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager      = $entityManager;
        $this->strategyRepository = $entityManager->getRepository('Application\Model\Entity\Strategy');
    }

    /**
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return StrategyEntity[]
     */
    public function getAllStrategies($userEntity)
    {
        return $this->strategyRepository->getAll($userEntity);
    }

    /**
     * @param integer $strategyID
     * @param \Application\Model\Entity\User $userEntity
     *
     * @return StrategyEntity
     */
    public function getStrategyById($strategyID, $userEntity)
    {
        return $this->strategyRepository->getStrategyById($strategyID, $userEntity);
    }

    /**
     * @param StrategyEntity $strategyEntity
     */
    public function dropStrategy($strategyEntity)
    {
        $this->entityManager->remove($strategyEntity);
        $this->entityManager->flush();
    }

    /**
     * @param StrategyEntity $strategyEntity
     *
     * @return StrategyEntity|null
     */
    public function save(StrategyEntity $strategyEntity)
    {
        $strategyEntity->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($strategyEntity);
        $this->entityManager->flush();
        return $strategyEntity;
    }

    /**
     * @param StrategyEntity $strategyEntity
     * @param $userEntity
     * @param array $items
     *
     * @throws \Api\Model\Exception
     * @return StrategyEntity|null
     */
    public function bindStrategyItems(StrategyEntity $strategyEntity, $userEntity, $items)
    {
        $strategyEntity->setUpdatedAt(new \DateTime());

        $sort = 0;
        foreach ($items as $item) {

            if (!in_array($item['type'], StrategyItemEntity::$allowTypes)) {
                throw new \Api\Model\Exception('Тип вставленного обьекта неизвестен');
            }
            $setting = FactorySettings::get($item['type'], $item['settings'], true);
            if (!$setting->isValidate()) {
                throw new \Api\Model\Exception('Настройки воспроизведения некоректны');
            }
            ;

            $newItemEntity = new StrategyItemEntity();
            $newItemEntity->setType($item['type']);
            $newItemEntity->setSettings($setting);
            $newItemEntity->setSort($sort++);
            $newItemEntity->setFkUser($userEntity);
            $newItemEntity->setFkStrategy($strategyEntity);

            $this->entityManager->persist($newItemEntity);
        }

        $this->entityManager->flush();
        return $strategyEntity;
    }

    /**
     * @param StrategyEntity $strategyEntity
     *
     * @return StrategyEntity
     */
    public function eraseStrategyItems(StrategyEntity $strategyEntity)
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->delete('\Application\Model\Entity\StrategyItem', 'item');
        $qb->where($qb->expr()->eq('item.fkStrategy', ':strategy'));

        $qb->setParameter('strategy', $strategyEntity);

        $qb->getQuery()->execute();
    }

    public function getItemTemplate($itemType, $settings = [])
    {
        switch ($itemType) {
            case StrategyItemEntity::TYPE_TARGET:
            case StrategyItemEntity::TYPE_SOURCE:
            case StrategyItemEntity::TYPE_SILENCE:
                return [
                    'type'     => $itemType,
                    'settings' => $settings
                ];
        }
        return [];
    }
}