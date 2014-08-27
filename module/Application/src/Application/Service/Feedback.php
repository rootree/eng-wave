<?php

namespace Application\Service;

use Application\Model\Repository\Feedback as FeedbackRepository;
use Application\Model\Entity\Feedback as FeedbackEntity;
use Application\Model\Entity\WordsGroup as WordsGroupEntity;
use Doctrine\ORM\EntityManager;

class Feedback
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var FeedbackRepository
     */
    private $feedbackRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->feedbackRepository = $entityManager->getRepository('Application\Model\Entity\Feedback');
    }

    /**
     * @param FeedbackEntity $feedbackEntity
     *
     * @return FeedbackEntity
     */
    public function save($feedbackEntity)
    {
        $feedbackEntity->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($feedbackEntity);
        $this->entityManager->flush();

        return $feedbackEntity;
    }
}
