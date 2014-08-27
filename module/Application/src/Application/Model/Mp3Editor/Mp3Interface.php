<?php

namespace Application\Model\Mp3Editor;

interface Mp3Interface
{
    public function __construct($path);
    public function mergeBehind($mp3);
    public function saveFile($path);
}
