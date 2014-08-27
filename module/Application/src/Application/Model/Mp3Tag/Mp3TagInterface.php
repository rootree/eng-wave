<?php

namespace Application\Model\Mp3Tag;

interface Mp3TagInterface
{
    public function __construct($path);

    /**
     * @param $title
     * @param null $artist
     * @param null $album
     * @param null $year
     * @param null $genre
     * @param null $comment
     * @param null $track
     *
     * @return bool
     * @throws \RuntimeException
     */
    public function editTag(
        $title, $artist  = null, $album   = null, $year    = null, $genre   = null, $comment = null, $track   = null
    );

    /**
     * @return integer
     */
    public function getPlaytimeInSeconds();
}
