<?php

class Npc extends Entity
{
    function __construct($id, $kind, $x, $y)
    {
        parent::__construct($id, "npc", $kind, $x, $y);
    }
}