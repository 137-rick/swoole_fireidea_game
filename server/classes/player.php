<?php

class Player extends Character
{
    public $server;
    public $connection;

    public $hasEnteredGame;

    public $name;
    public $armorLevel;
    public $armor;
    public $weapon;
    public $weaponLevel;

    public $zone_callback;
    public $move_callback;
    public $message_callback;
    public $exit_callback;
    public $broadcast_callback;
    public $broadcastzone_callback;
    public $lootmove_callback;
    public $orient_callback;
    public $requestpos_callback;

    public $firepotionTimeout;

    function __construct($connection, $worldServer)
    {
        $this->server = $worldServer;
        $this->connection = $connection;

        parent::__construct($this->connection->id, "player", TYPE_ENTITIES::WARRIOR, 0, 0, "");

        $this->hasEnteredGame = false;

        $this->isDead = false;
        $this->haters = array();
        $this->lastCheckpoint = null;
        $this->formatChecker = new FormatChecker();
        $this->disconnectTimeout = null;

        $this->connection->sendUTF8("go"); // Notify client that the HELLO/WELCOME handshake can start

    }

    public function handleMessage($message)
    {

        $action = $message[0];

        if ($this->formatChecker->check($message)) {
            $this->connection->close("Invalid " . TYPE_MESSAGE::getMessageTypeAsString($action) . " message format: " . $message);
        }

        if (!$this->hasEnteredGame && $action != TYPE_MESSAGE::HELLO) {
            $this->connection->close("Invalid handshake message: " . $message);
        }

        $this->resetTimeout();

        if ($action == TYPE_MESSAGE::HELLO) {
            $name = substr($message[1], 0, 30);
            $this->name = $name;
            $this->kind = TYPE_ENTITIES::WARRIOR;
            $this->equipArmor($message[2]);
            $this->equipWeapon($message[3]);
            $this->orientation = TYPE_ORIENTATIONS::DOWN;//todo:randowm
            $this->updateHitPoints();
            $this->updatePosition();

            $this->server->addPlayer($this);
            call_user_func($this->server->enter_callback, $this);

            $this->send(array(TYPE_MESSAGE::WELCOME, $this->id, $this->name, $this->x, $this->y, $this->hitPoints));
            $this->hasEnteredGame = true;
            $this->isDead = false;
        } else if ($action == TYPE_MESSAGE::WHO) {
            array_shift($message);
            $this->server->pushSpawnsToPlayer($this, $message);
        } else if ($action == TYPE_MESSAGE::ZONE) {
            call_user_func($this->zone_callback);
        } else if ($action == TYPE_MESSAGE::CHAT) {
            $msg = $message[1];
            if ($msg && $msg !== "") {
                $msg = substr($msg, 0, 60);
                $this->broadcastToZone(new Message_Chat($this, $msg), false);
            }
        } else if ($action == TYPE_MESSAGE::MOVE) {
            if ($this->move_callback) {
                $x = $message[1];
                $y = $message[2];

                if ($this->server->isValidPosition($x, $y)) {
                    $this->setPosition($x, $y);
                    $this->clearTarget();

                    $this->broadcast(new Message_Move($this));
                    call_user_func($this->move_callback, $this->x, $this->y);
                }
            }
        } else if ($action == TYPE_MESSAGE::LOOTMOVE) {
            if ($this->lootmove_callback) {
                $this->setPosition($message[1], $message[2]);


                $item = $this->server->getEntityById($message[3]);
                if ($item) {
                    $this->clearTarget();

                    $this->broadcast(new Message_LootMove($this, $item));
                    call_user_func($this->lootmove_callback, $this->x, $this->y);
                }
            }
        } else if ($action == TYPE_MESSAGE::AGGRO) {
            if ($this->move_callback) {
                $this->server->handleMobHate($message[1], $this->id, 5);
            }
        } else if ($action == TYPE_MESSAGE::ATTACK) {
            $mob = $this->server->getEntityById($message[1]);

            if ($mob) {
                $this->setTarget($mob);
                $this->server->broadcastAttacker($this);
            }
        } else if ($action == TYPE_MESSAGE::HIT) {
            $mob = $this->server->getEntityById($message[1]);
            if ($mob) {
                $dmg = Formulas::dmg($this->weaponLevel, $mob->armorLevel);

                if ($dmg > 0) {
                    $mob->receiveDamage($dmg, $this->id);
                    $mob->server->handleMobHate($mob->id, $this->id, $dmg);
                    $this->server->handleHurtEntity($mob, $dmg);
                }
            }
        } else if ($action == TYPE_MESSAGE::HURT) {
            $mob = $this->server->getEntityById($message[1]);
            if ($mob && $this->hitPoints > 0) {
                $this->hitPoints -= Formulas::dmg($mob->weaponLevel, $this->armorLevel);
                $this->server->handleHurtEntity($this);

                if ($this->hitPoints <= 0) {
                    $this->isDead = true;
                    //todo:timeout
                    if ($this->firepotionTimeout) {
                        clearTimeout($this->firepotionTimeout);
                    }
                }
            }
        } else if ($action == TYPE_MESSAGE::LOOT) {
            $item = $this->server->getEntityById($message[1]);

            if ($item) {
                $kind = $item["kind"];

                if (TYPE_KINDS::isItem($kind)) {
                    $this->broadcast($item->despawn());
                    $this->server->removeEntity($item);

                    if ($kind === TYPE_ENTITIES::FIREPOTION) {
                        $this->updateHitPoints();
                        $this->broadcast($this->equip(TYPE_ENTITIES::FIREFOX));
                        //todo:timeout
                        $this->firepotionTimeout = setTimeout(function () {
                            $this->broadcast($this->equip($this->armor)); // return to normal after 15 sec
                            $this->firepotionTimeout = null;
                        }, 15000);
                        //here ne
                        $msg = new Message_HitPoints($this->maxHitPoints);
                        $this->send($msg->getMessage());
                    } else if (TYPE_KINDS::isHealingItem($kind)) {

                        $amount = 0;

                        switch ($kind) {
                            case TYPE_ENTITIES::FLASK:
                                $amount = 40;
                                break;
                            case TYPE_ENTITIES::BURGER:
                                $amount = 100;
                                break;
                        }

                        if (!$this->hasFullHealth()) {
                            $this->regenHealthBy($amount);
                            $this->server->pushToPlayer($this, $this->health());
                        }
                    } else if (TYPE_KINDS::isArmor($kind) || TYPE_KINDS:: isWeapon($kind)) {
                        $this->equipItem($item);
                        $this->broadcast($this->equip($kind));
                    }
                }
            }
        } else if ($action == TYPE_MESSAGE::TELEPORT) {
            $x = $message[1];
            $y = $message[2];

            if ($this->server->isValidPosition($x, $y)) {
                $this->setPosition($x, $y);
                $this->clearTarget();

                $this->broadcast(new Message_Teleport($this));

                $this->server->handlePlayerVanish($this);
                $this->server->pushRelevantEntityListTo($this);
            }
        } else if ($action == TYPE_MESSAGE::OPEN) {
            $chest = $this->server->getEntityById($message[1]);
            if ($chest && $chest instanceof Chest) {
                $this->server->handleOpenedChest($chest, $this);
            }
        } else if ($action == TYPE_MESSAGE::CHECK) {
            $checkpoint = $this->server->map->getCheckpoint($message[1]);
            if ($checkpoint) {
                $this->lastCheckpoint = $checkpoint;
            }
        } else {
            if ($this->message_callback) {
                call_user_func($this->message_callback, $message);
            }
        }
    }

    public function connection_onclose()
    {
        //todo:timeout
        if ($this->firepotionTimeout) {
            clearTimeout($this->firepotionTimeout);
        }
        clearTimeout($this->disconnectTimeout);
        if ($this->exit_callback) {
            call_user_func($this->exit_callback);
        }
    }

    public function destroy()
    {
        $this->forEachAttacker(function ($mob) {
            $mob->clearTarget();
        });
        $this->attackers = array();

        $this->forEachHater(function ($mob) {
            $mob->forgetPlayer($this->id);
        });
        $this->haters = array();
    }

    public function getState()
    {
        $basestate = $this->_getBaseState();
        $state = array($this->name, $this->orientation, $this->armor, $this->weapon);

        if ($this->target) {
            $state[] = $this->target;
        }
        $basestate[] = $state;
        return $basestate;
    }

    public function send($message)
    {
        $this->connection->send($message);
    }

    public function broadcast($message, $ignorSelf = null)
    {
        if ($this->broadcast_callback) {
            call_user_func($this->broadcast_callback, $message, $ignorSelf == null ? true : $ignorSelf);
        }
    }

    public function broadcastToZone($message, $ignoreSelf = null)
    {
        if ($this->broadcastzone_callback) {
            call_user_func($this->broadcastzone_callback, $message, $ignoreSelf == null ? true : $ignoreSelf);
        }
    }

    public function onExit($callback)
    {
        $this->exit_callback = $callback;
    }

    public function onMove($callback)
    {
        $this->move_callback = $callback;
    }

    public function onLootMove($callback)
    {
        $this->lootmove_callback = $callback;
    }

    public function onZone($callback)
    {
        $this->zone_callback = $callback;
    }

    public function onOrient($callback)
    {
        $this->orient_callback = $callback;
    }

    public function onMessage($callback)
    {
        $this->message_callback = $callback;
    }

    public function onBroadcast($callback)
    {
        $this->broadcast_callback = $callback;
    }

    public function onBroadcastToZone($callback)
    {
        $this->broadcastzone_callback = $callback;
    }

    public function equip($item)
    {
        return new Message_EquipItem($this, $item);
    }

    public function addHater($mob)
    {
        if ($mob) {
            if (!isset($this->haters[$mob->id])) {
                $this->haters[$mob->id] = $mob;
            }
        }
    }

    public function removeHater($mob)
    {
        if (isset($this->haters[$mob->id])) {
            unset($this->haters[$mob->id]);
        }
    }

    public function forEachHater($callback)
    {
        foreach ($this->haters as $h) {
            $callback($h);
        }
    }

    public function equipArmor($kind)
    {
        $this->armor = $kind;
        $this->armorLevel = Properties::getArmorLevel($kind);
    }

    public function equipWeapon($kind)
    {
        $this->weapon = $kind;
        $this->weaponLevel = Properties::getWeaponLevel($kind);
    }

    public function equipItem($item)
    {
        if ($item) {
            //log->debug($this->name + " equips " + Types->getKindAsString(item->kind));

            if (TYPE_KINDS::isArmor($item->kind)) {
                $this->equipArmor($item->kind);
                $this->updateHitPoints();
                $msg = new Message_HitPoints($this->maxHitPoints);
                $this->send($msg->getMessage());
            } else if (TYPE_KINDS::isWeapon($item->kind)) {
                $this->equipWeapon($item->kind);
            }
        }
    }

    public function updateHitPoints()
    {
        $this->resetHitPoints(Formulas::hp($this->armorLevel));
    }

    public function updatePosition()
    {
        if ($this->requestpos_callback) {
            $pos = call_user_func($this->requestpos_callback);
            $this->setPosition($pos[0], $pos[1]);
        }
    }

    public function onRequestPosition($callback)
    {
        $this->requestpos_callback = $callback;
    }

    public function resetTimeout()
    {
        //todo:settimeout
        clearTimeout($this->disconnectTimeout);
        //todo:timeout 执行有问题
        $this->disconnectTimeout = setTimeout($this->timeout, 1000 * 60 * 15); // 15 min->
    }

    public function timeout()
    {
        $this->connection->sendUTF8("timeout");
        $this->connection->close("Player was idle for too long");
    }
}

