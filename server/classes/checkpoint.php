<?php

class checkPoint
{
    public $id;
    public $x;
    public $y;
    public $width;
    public $height;

    function __construct($id, $x, $y, $width, $height)
    {
        $this->id = $id;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    function getRandomPosition()
    {
        $pos = array(0, 0);
        $pos[0] = $this->x + mt_rand(0, $this->width - 1);
        $pos[1] = $this->y + mt_rand(0, $this->height - 1);
        return $pos;
    }
}