<?php

class MobArea extends Area
{
    public $nb;
    public $kind;
    public $respawns;


    function __construct($id, $nb, $kind, $x, $y, $width, $height, $world)
    {
        parent::__construct($id, $x, $y, $width, $height, $world);
        $this->nb = $nb;
        $this->kind = $kind;
        $this->respawns = array();
        $this->setNumberOfEntities($this->nb);

        //这里官方是注释de
        //$this->initRoaming();
    }

    public function spawnMobs()
    {
        for ($i = 0; $i < $this->nb; $i++) {
            $this->addToArea($this->_createMobInsideArea());
        }
    }

    public function _createMobInsideArea()
    {
        $k = TYPE_KINDS::getKindFromString($this->kind);
        $pos = $this->_getRandomPositionInsideArea();
        $mob = new Mob('1' . $this->id . "" . $k . "" . count($this->entities), $k, $pos["x"], $pos["y"]);

        $mob->onMove($this->world->onMobMoveCallback->bind($this->world));
        return $mob;
    }

    public function respawnMob($mob, $delay)
    {
        $this->removeFromArea($mob);

        $self = $this;

        //todo:settimeout
        etTimeout(function () {
            $pos = $self->_getRandomPositionInsideArea();

            $mob->x = $pos[0];
            $mob->y = $pos[1];
            $mob->isDead = false;
            $self->addToArea($mob);
            $self->world->addMob($mob);
        }, $delay);
    }

    public function initRoaming($mob, $delay)
    {
        /*
         反正没使用，没写
             initRoaming: function(mob) {
        var self = this;

        setInterval(function() {
            _.each(self.entities, function(mob) {
                var canRoam = (Utils.random(20) === 1),
                    pos;

                if(canRoam) {
                    if(!mob.hasTarget() && !mob.isDead) {
                        pos = self._getRandomPositionInsideArea();
                        mob.move(pos.x, pos.y);
                    }
                }
            });
        }, 500);
    },
         */
    }

    public function createReward()
    {
        $pos = $this->_getRandomPositionInsideArea();
        return array("x" => $pos[0], "y" => $pos[1], "kind" => TYPE_ENTITIES::CHEST);
    }
}
