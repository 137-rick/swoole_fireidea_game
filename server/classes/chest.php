<?php

class Chest extends Item
{
    public $items;

    function __construct($id, $x, $y)
    {
        parent::__construct($id, TYPE_ENTITIES::CHEST, $x, $y);
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getRandomItem()
    {
        $nbItems = count($this->items);
        $item = null;
        if ($nbItems > 0) {
            $item = $this->items[mt_rand(0,$nbItems)];
        }
        return $item;
    }
}
