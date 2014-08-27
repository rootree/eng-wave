<?php

namespace Application\Model\Speaker;

abstract class Service extends SpeakerAbstract
{
    protected $availableVoices = [];
    protected $linksByVoices = [];

    public function isCanSpeak($languageCode)
    {
        return in_array($languageCode, $this->availableVoices);
    }

    protected function getLinkForSpeak()
    {
        if (!isset($this->linksByVoices[$this->speakInLanguage])) {
            throw new \RuntimeException(sprintf('%s speaker does not anything about selected language (type: %s)',
                get_called_class(), $this->speakInLanguage));
        }
        return $this->linksByVoices[$this->speakInLanguage];
    }

    /**
     * @param $wordsForSpeaking
     *
     * @return string
     */
    public function getWordsFileContent($wordsForSpeaking)
    {
        return @file_get_contents(sprintf($this->getLinkForSpeak(), urlencode($wordsForSpeaking.'.')));
    }
}
