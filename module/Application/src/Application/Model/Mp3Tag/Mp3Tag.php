<?php

namespace Application\Model\Mp3Tag;

class Mp3Tag implements Mp3TagInterface
{
    private $mp3File = null;
    private $textEncoding = 'UTF-8';

    // Create a new mp3
    public function __construct($path)
    {
        $this->mp3File = $path;
    }

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
        $title, $artist = null, $album = null, $year = null, $genre = null, $comment = null, $track = null
    )
    {


        $tagwriter = new \GetId3\Write\Tags();

        $tagwriter->filename = $this->mp3File;
        $tagwriter->tagformats = array('id3v1', 'id3v2.3'); // , 'lyrics3'
        // $tagwriter->tagformats = array('lyrics3');
        //$tagwriter->overwrite_tags = true;
        $tagwriter->tag_encoding      = $this->textEncoding;
        $tagwriter->remove_other_tags = true;

        // populate data array
        $TagData = array(
            'title'   => array($title),
            'artist'  => array($artist),
            'album'   => array($album),
            'year'    => array($year),
            'genre'   => array($genre),
            'comment' => array($comment),
            'track'   => array($track),
            'lyricist' => [
                'data' => "[00:02]Let's talk about time\r\n[00:02]tickin' away every day\r\n[00:05]so wake on up before it's gone away\r\n",
            ]
        );
        $tagwriter->tag_data = $TagData;

        // write tags
        if (!$tagwriter->WriteTags()) {
            throw new \RuntimeException(implode(PHP_EOL, $tagwriter->errors));
        }

        if (!empty($tagwriter->warnings)) {
            // echo 'There were some warnings:<br>' . implode('<br><br>', $tagwriter->warnings);
        }
        // Initialize getID3 tag-writing module
      //  $GetId3 = new \GetId3\Handler\GetId3();
      //  $lyrics3 = new \GetId3\Module\Tag\Lyrics3($tagwriter);

        return true;
    }

    /**
     * @return integer
     */
    public function getPlaytimeInSeconds()
    {
        // Initialize getID3 engine
        $getID3 = new \GetId3\GetId3Core();
        $getID3->setOption(array('encoding' => $this->textEncoding));

        $thisFileInfo = $getID3->analyze($this->mp3File);
        //$len= @$ThisFileInfo['playtime_string'];

        return round(@$thisFileInfo['playtime_seconds']);
    }
}
