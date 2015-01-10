<?php

class Area
{
    public $id;
    public $x;
    public $y;
    public $width;
    public $height;
    public $world;
    public $entities;
    public $hasCompletelyRespawned;

    //entities 个数
    public $nbEntities;

    //当为空的时候的回调
    public $empty_callback;

    function __construct($id, $x, $y, $width, $height, $world)
    {
        $this->id = $id;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
        $this->world = $world;
        $this->entities = array();
        $this->hasCompletelyRespawned = true;
    }

    public function _getRandomPositionInsideArea()
    {
        $pos = array();
        $valid = false;
        while (!$valid) {
            $pos[0] = $this->x + mt_rand(0, $this->width + 1);
            $pos[1] = $this->y + mt_rand(0, $this->height + 1);
            $valid = $this->world->isValidPosition($pos[0], $pos[1]);
        }
        return $pos;
    }

    public function removeFromArea($entity)
    {
        //找到对象并删除他
        foreach ($this->entities as $k => $en) {
            if ($en["id"] == $entity["id"]) {
                //删除他
                $this->entities[$k];
                break;
            }
        }
        //检测区域是否空了
        if ($this->isEmpty() && $this->hasCompletelyRespawned && $this->empty_callback) {
            $this->hasCompletelyRespawned = false;
            //调用回调
            call_user_func($this->empty_callback);
        }

    }

    public function addToArea($entity)
    {
        if ($entity) {
            $this->entities[] = $entity;
            $entity["area"] = $this;

            if ($entity instanceof Mob) {
                $this->world->addMob($entity);
            }
        }
        if ($this->isFull()) {
            $this->hasCompletelyRespawned = true;
        }
    }

    public function setNumberOfEntities($nb)
    {
        $this->nbEntities = $nb;
    }

    //检测区域是否空了
    public function isEmpty()
    {
        foreach ($this->entities as $k => $en) {
            //如果有个家伙没挂，那么没空
            //is dead 当为 false代表没挂
            if (!$en->isDead()) {
                //没空
                return false;
            }
        }
        //区域空了
        return true;
    }

    public function isFull()
    {
        //没空，并且当前实体个数等于当前活动实体个数……卧槽这是啥
        if (!$this->isEmpty() && ($this->nbEntities === count($this->entities))) {
            return true;
        } else {
            return false;
        }
    }

    //注册当世界空了后调用指定回调
    public function  onEmpty($callback)
    {
        $this->empty_callback = $callback;
    }

}