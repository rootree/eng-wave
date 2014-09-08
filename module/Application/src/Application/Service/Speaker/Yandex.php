<?php

namespace Application\Service\Speaker;

class Yandex extends Service
{
    protected $availableVoices = [
        parent::SPEAK_IN_RUSSIAN,
        parent::SPEAK_IN_ENGLISH
    ];

    protected $linksByVoices = [
        parent::SPEAK_IN_RUSSIAN => 'http://tts.voicetech.yandex.net/tts?format=mp3&quality=hi&platform=web&application=translate&lang=ru_RU&text=%s',
        parent::SPEAK_IN_ENGLISH => 'http://tts.voicetech.yandex.net/tts?format=mp3&quality=hi&platform=web&application=translate&lang=en_GB&text=%s'
    ];
}
