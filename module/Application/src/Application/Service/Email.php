<?php

namespace Application\Service;

use Application\Model\Repository\Strategy as StrategyRepository;
use Application\Model\Entity\Strategy as StrategyEntity;
use Application\Model\Entity\StrategyItem as StrategyItemEntity;
use Application\Model\Entity\StrategyItemSettings\Silent as SilentSettings;
use Application\Model\Entity\StrategyItemSettings\Factory as FactorySettings;
use Doctrine\ORM\EntityManager;
use Zend\Mail;

use Application\Model\Entity\Download as DownloadEntity;

class Email
{
    protected $settings = [];
    /**
     * @param array $settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function sendDownloadMessage(DownloadEntity $downloadEntity)
    {
        $userEntity = $downloadEntity->getFkUser();

        $downloadURL = sprintf('%sdownload/hash/%s', $this->settings['url'], $downloadEntity->getHash());

        $mail = new Mail\Message();
        $mail->setFrom('no-replay@engwave.com', 'EngWave robot');
        $mail->setBody($downloadURL);
        $mail->addTo($userEntity->getEmail(), $userEntity->getName());
        $mail->setSubject($downloadEntity->getFkWordsGroup()->getTitle());

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }
}
