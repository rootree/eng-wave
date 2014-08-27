<?php

namespace Application\Service;

use Application\Model\Repository\Language as LanguageRepository;
use Application\Entity\Language as LanguageEntity;
use Doctrine\ORM\EntityManager;

class Language
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->languageRepository = $entityManager->getRepository('Application\Model\Entity\Language');
    }

    /**
     * @return LanguageEntity[]
     */
    public function getAllLanguages()
    {
        return $this->languageRepository->getLanguages();
    }
}
