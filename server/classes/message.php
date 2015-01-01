<?php

class Message_Spawn
{
    private $entity;

    function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::SPAWN) + $this->entity->getState();
    }

}

class Message_Despawn
{
    private $entityId;

    function __construct($entityId)
    {
        $this->entityId = $entityId;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::DESPAWN, $this->entityId);
    }

}

class Message_Move
{
    private $entity;

    function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::MOVE, $this->entity->id, $this->entity->x, $this->entity->y);
    }

}

class Message_LootMove
{
    private $entity;
    private $item;

    function __construct($entity, $item)
    {
        $this->entity = $entity;
        $this->item = $item;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::LOOTMOVE, $this->entity->id, $this->item->id);
    }
}

class Message_Attack
{
    private $attackerId;
    private $targetId;

    function __construct($attackerId, $targetId)
    {
        $this->attackerId = $attackerId;
        $this->targetId = $targetId;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::ATTACK, $this->attackerId, $this->targetId);
    }
}

class Message_Health
{
    private $points;
    private $isRegen;

    function __construct($points, $isRegen)
    {
        $this->points = $points;
        $this->isRegen = $isRegen;
    }

    public function getMessage()
    {
        $heal = array(TYPE_MESSAGE::HEALTH, $this->points);
        if ($this->isRegen) {
            $heal[] = 1;
        }
        return $heal;
    }
}

class Message_HitPoints
{
    private $maxHitPoints;

    function __construct($maxHitPoints)
    {
        $this->maxHitPoints = $maxHitPoints;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::HP, $this->maxHitPoints);
    }
}

class Message_EquipItem
{
    private $playerId;
    private $itemKind;

    function __construct($player, $itemKind)
    {
        $this->playerId = $player->id;
        $this->itemKind = $itemKind;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::EQUIP, $this->playerId, $this->itemKind);
    }
}

class Message_Drop
{
    private $mob;
    private $item;

    function __construct($mob, $item)
    {
        $this->mob = $mob;
        $this->item = $item;
    }

    public function getMessage()
    {
        $getid = function ($value) {
            return $value["id"];
        };
        return array(TYPE_MESSAGE::DROP, $this->mob->id, $this->item->id, $this->item->kind, array_map($getid, $this->mob->hatelist));
    }
}

class Message_Chat
{
    private $playerId;
    private $message;

    function __construct($player, $message)
    {
        $this->playerId = $player->id;
        $this->message = $message;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::CHAT, $this->playerId, $this->message);
    }
}

class Message_Teleport
{
    private $entity;

    function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::TELEPORT, $this->entity->id, $this->entity->x, $this->entity->y);
    }
}

class Message_Damage
{
    private $entity;
    private $points;

    function __construct($entity, $points)
    {
        $this->entity = $entity;
        $this->points = $points;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::DAMAGE, $this->entity->id, $this->points);
    }
}

class Message_Population
{
    private $world;
    private $total;

    function __construct($world, $total)
    {
        $this->world = $world;
        $this->total = $total;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::POPULATION, $this->world, $this->total);
    }
}

class Message_Kill
{
    private $mob;

    function __construct($mob)
    {
        $this->mob = $mob;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::KILL, $this->mob->kind);
    }
}

class Message_List
{
    private $ids;

    function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::_LIST) + $this->ids;
    }
}

class Message_Destroy
{
    private $entity;

    function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::DESTROY) + $this->entity->id;
    }
}

class Message_Blink
{
    private $item;

    function __construct($item)
    {
        $this->item = $item;
    }

    public function getMessage()
    {
        return array(TYPE_MESSAGE::BLINK) + $this->item->id;
    }
}