<?php

namespace Application\Service\Speaker;

abstract class SpeakerAbstract
{
    const SPEAK_IN_RUSSIAN = 'ru';
    const SPEAK_IN_ENGLISH = 'en';
    const SPEAK_IN_GERMANY = 'ge';

    protected $speakInLanguage = null;

    protected $loggerService = null;

    public function __construct($languageType)
    {
        if (!$this->isCanSpeak($languageType)) {
            throw new \RuntimeException(sprintf('Unsupported language type (%s) for %s speaker', $languageType, get_called_class()));
        }
        $this->speakInLanguage = $languageType;
    }

    /**
     * @param $languageCode
     *
     * @return bool
     */
    abstract public function isCanSpeak($languageCode);

    /**
     * @param $wordsForSpeaking
     *
     * @return string
     */
    abstract public function getWordsFileContent($wordsForSpeaking);

    /**
     * @param $loggerService
     * @param $loggerService
     */
    abstract public function setLogger($loggerService);

    /**
     * @param $string
     *
     * @return mixed
     */
    protected function dropUnreadableSymbols($string)
    {
        $string = str_replace('#$%&\'*+-/=^_`{|}~@[]', '', $string); // .?!
        //$string = preg_replace('/[^\x00-\x7F]+/', '', $string);
        return $string;
    }
}
