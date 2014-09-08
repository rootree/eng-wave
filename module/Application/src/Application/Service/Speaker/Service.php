<?php

namespace Application\Service\Speaker;

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

    public function setLogger($loggerService)
    {
        $this->loggerService = $loggerService;
    }

    /**
     * @param $wordsForSpeaking
     *
     * @return string
     */
    public function getWordsFileContent($wordsForSpeaking)
    {
        $ctx = stream_context_create(array('http'=>
           array(
               'timeout' => 3, // 1 200 Seconds = 20 Minutes
           )
        ));
        $url = sprintf($this->getLinkForSpeak(), urlencode($this->dropUnreadableSymbols($wordsForSpeaking).'.'));
        $result = @file_get_contents($url, false, $ctx);
        if (!$result) {
            $this->loggerService->err(sprintf('Speaker URL can not be reached: %s', $url));
        }
        return $result;
    }
}
