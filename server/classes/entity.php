<?php

class Entity
{
    public $id;
    public $type;
    public $kind;
    public $x;
    public $y;

    function __construct($id, $type, $kind, $x, $y)
    {
        $this->id = $id;
        $this->type = $type;
        $this->kind = $kind;
        $this->x = $x;
        $this->y = $y;
    }

    public function _getBaseState()
    {
        return array($this->id, $this->kind, $this->x, $this->y);
    }

    public function getState()
    {
        return $this->_getBaseState();
    }

    public function spawn()
    {
        $msgObj = new Message_Spawn($this);
        return $msgObj;
    }

    public function despawn()
    {
        $msgObj = new Message_Despawn($this);
        return $msgObj;
    }

    public function  setPosition($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getPositionNextTo($entity)
    {
        $pos = null;
        if ($entity) {
            $pos = array($entity->x, $entity->y);
            $r = (int)(mt_rand(0, 4));
            if ($r === 0) $pos["y"] -= 1;
            if ($r === 1) $pos["y"] += 1;
            if ($r === 2) $pos["x"] -= 1;
            if ($r === 3) $pos["x"] += 1;
        }
        return $pos;
    }
}