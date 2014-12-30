<?php

class ChestArea extends Area
{
    public $items;
    public $chestx;
    public $chesty;

    function __construct($id, $x, $y, $width, $height, $cx, $cy, $items, $world)
    {
        parent::__construct($id, $x, $y, $width, $height, $world);
        $this->items = $items;
        $this->chestx = $cx;
        $this->chesty = $cy;
    }

    public function contains($entity)
    {
        if ($entity) {
            return $entity->x >= $this->x
            && $entity->y >= $this->y
            && $entity->x < $this->x + $this->width
            && $entity->y < $this->y + $this->height;
        } else {
            return false;
        }
    }
}
