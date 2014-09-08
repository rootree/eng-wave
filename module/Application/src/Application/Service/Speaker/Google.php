<?php

namespace Application\Service\Speaker;

class Google extends Service
{
    protected $availableVoices = [
        parent::SPEAK_IN_RUSSIAN,
        parent::SPEAK_IN_ENGLISH
    ];

    protected $linksByVoices = [
        parent::SPEAK_IN_RUSSIAN => 'https://translate.google.ru/translate_tts?ie=UTF-8&q=%s&tl=ru&total=1&idx=10&textlen=15&client=t&prev=input',
        parent::SPEAK_IN_ENGLISH => 'https://translate.google.ru/translate_tts?ie=UTF-8&q=%s&tl=en&total=1&idx=10&textlen=15&client=t&prev=input'
    ];
}
