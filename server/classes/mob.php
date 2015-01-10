<?php

class Mob extends Character
{
    public $spawningX;
    public $spawningY;
    public $armorLevel;
    public $weaponLevel;
    public $hatelist;
    public $respawnTimeout;
    public $returnTimeout;
    public $isDead;
    public $area;
    public $respawn_callback;
    public $move_callback;


    function __construct($id, $kind, $x, $y)
    {
        parent::__construct($id, "mob", $kind, $x, $y);

        $this->spawningX = $x;
        $this->spawningY = $y;
        $this->armorLevel = Properties::getArmorLevel($this->kind);
        $this->weaponLevel = Properties::getWeaponLevel($this->kind);
        $this->hatelist = array();
        $this->respawnTimeout = null;
        $this->returnTimeout = null;
        $this->isDead = false;
    }

    public function destroy()
    {
        $this->isDead = true;
        $this->hatelist = array();
        $this->clearTarget();
        $this->updateHitPoints();
        $this->resetPosition();

        $this->handleRespawn();

    }

    public function receiveDamage($points, $playerId)
    {
        $this->hitPoints -= $points;
    }

    public function hates($playerId)
    {
        foreach ($this->hatelist as $k => $hitem) {
            if ($hitem == $playerId) {
                return $k;
            }
        }
        return false;
    }

    public function increaseHateFor($playerId, $points)
    {
        $hateid = $this->hates($playerId);
        if ($hateid != false) {
            $this->hatelist[$hateid]["hate"] += $points;
        } else {
            $this->hatelist[] = array("id" => $playerId, "hate" => $points);
        }
        if ($this->returnTimeout) {
            clearTimeout($this->returnTimeout);//todo: there is no cleartime
            $this->returnTimeout = null;
        }
    }

    public function  getHatedPlayerId($hateRank)
    {
        //todo:here not sure work
        $arrCmp =
            function ($a, $b) {
                if ($a['hate'] == $b['hate']) {
                    return 0;
                }
                return ($a['hate'] < $b['hate']) ? -1 : 1;
            };

        usort($new, array($this->hatelist, $arrCmp));
        $count = count($this->hatelist);
        if ($hateRank && $hateRank <= $count) {
            $i = $count - $hateRank;
        } else {
            $i = $count - 1;
        }
        if ($new && $new[$i]) {
            $playerId = $new[$i]["id"];
        }
        return $playerId;
    }

    public function forgetPlayer($playerId, $duration)
    {
        foreach ($this->hatelist as $k => $hitem) {
            if ($hitem["id"] == $playerId) {
                unset($this->hatelist[$k]);
                break;
            }
        }

        if (count($this->hatelist) == 0) {
            $this->returnToSpawningPosition($duration);
        }
    }

    public function forgetEveryone()
    {
        $this->hatelist = array();
        $this->returnToSpawningPosition(1);
    }

    public function drop($item)
    {
        if ($item) {
            return new Message_Drop($this, $item);
        }
        return null;
    }

    public function handleRespawn()
    {
        $delay = 30000;

        if ($this->area && $this->area instanceof MobArea) {
            $this->area->respawnMob($this, $delay);
        } else {
            if ($this->area && $this->area instanceof ChestArea) {
                $this->area->removeFromArea($this);
            }

            //todo:no time out
            setTimeout(function () {
                if ($this->respawn_callback) {
                    $this->respawn_callback();
                }
            }, $delay);
        }
    }

    public function onRespawn($callback)
    {
        $this->respawn_callback = $callback;
    }

    public function resetPosition()
    {
        $this->setPosition($this->spawningX, $this->spawningY);
    }

    public function returnToSpawningPosition($waitDuration)
    {
        if (!$waitDuration) {
            $delay = 4000;
        } else {
            $delay = $waitDuration;
        }
        $this->clearTarget();
        //todo:setTimeOut
        $this->returnTimeout = setTimeout(function () {
            $this->resetPosition();
            $this->move($this->x, $this->y);
        }, $delay);
    }

    public function onMove($callback)
    {
        $this->move_callback = $callback;
    }

    public function move($x, $y)
    {
        $this->setPosition($x, $y);
        if ($this->move_callback) {
            $this->move_callback($this);
        }
    }

    public function updateHitPoints()
    {
        $this->resetPosition(Properties::getHitPoints($this->kind));
    }

    public function distanceToSpawningPoint($x, $y)
    {
        return Utils::distanceTo($x, $y, $this->spawningX, $this->spawningY);
    }
}
