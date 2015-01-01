<?php

class Character extends Entity
{
    public $orientation;
    public $attackers;
    public $target;

    public $maxHitPoints;
    public $hitPoints;

    function __construct($id, $type, $kind, $x, $y)
    {
        parent::__construct($id, $type, $kind, $x, $y);

        $this->orientation = TYPE_ORIENTATIONS::DOWN; //todo: Utils.randomOrientation();
        $this->attackers = array();
        $this->target = null;
    }


    public function getState()
    {
        $basestate = $this->_getBaseState();

        $basestate[] = $this->orientation;
        if ($this->target) {
            $basestate[] = $this->target;
        }
        //这东西有顺序
        return $basestate;
    }

    public function resetHitPoints($maxHitPoints)
    {
        $this->maxHitPoints = $maxHitPoints;
        $this->hitPoints = $this->maxHitPoints;
    }

    public function regenHealthBy($value)
    {
        $hp = $this->hitPoints;
        $max = $this->maxHitPoints;

        if ($hp < $max) {
            if ($hp + $value <= $max) {
                $this->hitPoints += $value;
            } else {
                $this->hitPoints = $max;
            }
        }
    }

    public function hasFullHealth()
    {
        return $this->hitPoints == $this->maxHitPoints;
    }

    public function setTarget($entity)
    {
        $this->target = $entity->id;
    }

    public function  clearTarget()
    {
        $this->target = null;
    }

    public function hasTarget()
    {
        return $this->target != null;
    }

    public function attack()
    {
        return new Message_Attack($this->id, $this->target);
    }

    public function health()
    {
        return new Message_Health($this->hitPoints, false);
    }

    public function regen()
    {
        return new Message_Health($this->hitPoints, true);
    }

    public function  addAttacker($entity)
    {
        if ($entity) {
            $this->attackers[$entity->id] = $entity;
        }
    }

    public function removeAttacker($entity)
    {
        if ($entity && $this->attackers[$entity->id]) {
            unset($this->attackers[$entity->id]);
        }
    }

    public function forEachAttacker($callback)
    {
        foreach ($this->attackers as $attacker) {
            $callback($attacker);
        }
    }
}
