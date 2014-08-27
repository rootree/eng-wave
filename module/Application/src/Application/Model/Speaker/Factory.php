<?php

namespace Application\Model\Speaker;

class Factory
{
    const TYPE_YANDEX = 1;
    const TYPE_GOOGLE = 1;

    /**
     * @param $speakerType
     * @param $languageType
     *
     * @throws \RuntimeException
     * @return SpeakerAbstract
     */
    static public function getSpeaker($speakerType, $languageType)
    {
        switch ($speakerType) {
            case Factory::TYPE_YANDEX:
                return new Yandex($languageType);
            case Factory::TYPE_GOOGLE:
                return new Google($languageType);
        }
        throw new \RuntimeException(sprintf('Unknown speaker type (%s)', $speakerType));
    }
}
