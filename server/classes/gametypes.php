<?php

/*
 *
 *  做了两种定义，常量列表，以及组合数据列表
 */

class TYPE_MESSAGE
{
    const HELLO = 0;
    const WELCOME = 1;
    const SPAWN = 2;
    const DESPAWN = 3;
    const MOVE = 4;
    const LOOTMOVE = 5;
    const AGGRO = 6;
    const ATTACK = 7;
    const HIT = 8;
    const HURT = 9;
    const HEALTH = 10;
    const CHAT = 11;
    const LOOT = 12;
    const EQUIP = 13;
    const DROP = 14;
    const TELEPORT = 15;
    const DAMAGE = 16;
    const POPULATION = 17;
    const KILL = 18;
    const _LIST = 19;
    const WHO = 20;
    const ZONE = 21;
    const DESTROY = 22;
    const HP = 23;
    const BLINK = 24;
    const OPEN = 25;
    const CHECK = 26;

    public static $mtype; //数组组织的type，没办法，const比较适合被引用，数组比较方便容易查找，为了性能只好如此了

    public static function init()
    {
        if (!self::$mtype) {
            self::$mtype = array(
                "HELLO" => "0",
                "WELCOME" => "1",
                "SPAWN" => "2",
                "DESPAWN" => "3",
                "MOVE" => "4",
                "LOOTMOVE" => "5",
                "AGGRO" => "6",
                "ATTACK" => "7",
                "HIT" => "8",
                "HURT" => "9",
                "HEALTH" => "10",
                "CHAT" => "11",
                "LOOT" => "12",
                "EQUIP" => "13",
                "DROP" => "14",
                "TELEPORT" => "15",
                "DAMAGE" => "16",
                "POPULATION" => "17",
                "KILL" => "18",
                "_LIST" => "19",
                "WHO" => "20",
                "ZONE" => "21",
                "DESTROY" => "22",
                "HP" => "23",
                "BLINK" => "24",
                "OPEN" => "25",
                "CHECK" => "26",
            );
        }
    }

    public static function getMessageTypeAsString($type)
    {
        self::init();
        foreach (self::$mtype as $k => $val) {
            if ($val == $type) {
                return $k;
            }
        }
        return "UNKNOWN";
    }

}

/**
 * Class TYPE_ENTITIES
 * 物品定义
 */
class TYPE_ENTITIES
{
    const WARRIOR = 1;

    // Mobs
    const RAT = 2;
    const SKELETON = 3;
    const GOBLIN = 4;
    const OGRE = 5;
    const SPECTRE = 6;
    const CRAB = 7;
    const BAT = 8;
    const WIZARD = 9;
    const EYE = 10;
    const SNAKE = 11;
    const SKELETON2 = 12;
    const BOSS = 13;
    const DEATHKNIGHT = 14;

    // Armors
    const FIREFOX = 20;
    const CLOTHARMOR = 21;
    const LEATHERARMOR = 22;
    const MAILARMOR = 23;
    const PLATEARMOR = 24;
    const REDARMOR = 25;
    const GOLDENARMOR = 26;

    // Objects
    const FLASK = 35;
    const BURGER = 36;
    const CHEST = 37;
    const FIREPOTION = 38;
    const CAKE = 39;

    // NPCs
    const GUARD = 40;
    const KING = 41;
    const OCTOCAT = 42;
    const VILLAGEGIRL = 43;
    const VILLAGER = 44;
    const PRIEST = 45;
    const SCIENTIST = 46;
    const AGENT = 47;
    const RICK = 48;
    const NYAN = 49;
    const SORCERER = 50;
    const BEACHNPC = 51;
    const FORESTNPC = 52;
    const DESERTNPC = 53;
    const LAVANPC = 54;
    const CODER = 55;

    // Weapons
    const SWORD1 = 60;
    const SWORD2 = 61;
    const REDSWORD = 62;
    const GOLDENSWORD = 63;
    const MORNINGSTAR = 64;
    const AXE = 65;
    const BLUESWORD = 6;
}

/**
 * Class TYPE_ORIENTATIONS
 * 方向定义
 */
class TYPE_ORIENTATIONS
{

    const UP = 1;
    const DOWN = 2;
    const LEFT = 3;
    const RIGHT = 4;

    public static function getOrientationAsString($orientation)
    {
        switch ($orientation) {
            case TYPE_ORIENTATIONS::LEFT:
                return "left";
                break;
            case TYPE_ORIENTATIONS::RIGHT:
                return "right";
                break;
            case TYPE_ORIENTATIONS::UP:
                return "up";
                break;
            case TYPE_ORIENTATIONS::DOWN:
                return "down";
                break;
        }
        return "";
    }
}


class TYPE_KINDS
{
    public static $kinds;

    public static function init()
    {
        if (!self::$kinds) {
            self::$kinds = array(
                "warrior" => array(TYPE_ENTITIES::WARRIOR, "player"),

                "rat" => array(TYPE_ENTITIES::RAT, "mob"),
                "skeleton" => array(TYPE_ENTITIES::SKELETON, "mob"),
                "goblin" => array(TYPE_ENTITIES::GOBLIN, "mob"),
                "ogre" => array(TYPE_ENTITIES::OGRE, "mob"),
                "spectre" => array(TYPE_ENTITIES::SPECTRE, "mob"),
                "deathknight" => array(TYPE_ENTITIES::DEATHKNIGHT, "mob"),
                "crab" => array(TYPE_ENTITIES::CRAB, "mob"),
                "snake" => array(TYPE_ENTITIES::SNAKE, "mob"),
                "bat" => array(TYPE_ENTITIES::BAT, "mob"),
                "wizard" => array(TYPE_ENTITIES::WIZARD, "mob"),
                "eye" => array(TYPE_ENTITIES::EYE, "mob"),
                "skeleton2" => array(TYPE_ENTITIES::SKELETON2, "mob"),
                "boss" => array(TYPE_ENTITIES::BOSS, "mob"),

                "sword1" => array(TYPE_ENTITIES::SWORD1, "weapon"),
                "sword2" => array(TYPE_ENTITIES::SWORD2, "weapon"),
                "axe" => array(TYPE_ENTITIES::AXE, "weapon"),
                "redsword" => array(TYPE_ENTITIES::REDSWORD, "weapon"),
                "bluesword" => array(TYPE_ENTITIES::BLUESWORD, "weapon"),
                "goldensword" => array(TYPE_ENTITIES::GOLDENSWORD, "weapon"),
                "morningstar" => array(TYPE_ENTITIES::MORNINGSTAR, "weapon"),

                "firefox" => array(TYPE_ENTITIES::FIREFOX, "armor"),
                "clotharmor" => array(TYPE_ENTITIES::CLOTHARMOR, "armor"),
                "leatherarmor" => array(TYPE_ENTITIES::LEATHERARMOR, "armor"),
                "mailarmor" => array(TYPE_ENTITIES::MAILARMOR, "armor"),
                "platearmor" => array(TYPE_ENTITIES::PLATEARMOR, "armor"),
                "redarmor" => array(TYPE_ENTITIES::REDARMOR, "armor"),
                "goldenarmor" => array(TYPE_ENTITIES::GOLDENARMOR, "armor"),

                "flask" => array(TYPE_ENTITIES::FLASK, "object"),
                "cake" => array(TYPE_ENTITIES::CAKE, "object"),
                "burger" => array(TYPE_ENTITIES::BURGER, "object"),
                "chest" => array(TYPE_ENTITIES::CHEST, "object"),
                "firepotion" => array(TYPE_ENTITIES::FIREPOTION, "object"),

                "guard" => array(TYPE_ENTITIES::GUARD, "npc"),
                "villagegirl" => array(TYPE_ENTITIES::VILLAGEGIRL, "npc"),
                "villager" => array(TYPE_ENTITIES::VILLAGER, "npc"),
                "coder" => array(TYPE_ENTITIES::CODER, "npc"),
                "scientist" => array(TYPE_ENTITIES::SCIENTIST, "npc"),
                "priest" => array(TYPE_ENTITIES::PRIEST, "npc"),
                "king" => array(TYPE_ENTITIES::KING, "npc"),
                "rick" => array(TYPE_ENTITIES::RICK, "npc"),
                "nyan" => array(TYPE_ENTITIES::NYAN, "npc"),
                "sorcerer" => array(TYPE_ENTITIES::SORCERER, "npc"),
                "agent" => array(TYPE_ENTITIES::AGENT, "npc"),
                "octocat" => array(TYPE_ENTITIES::OCTOCAT, "npc"),
                "beachnpc" => array(TYPE_ENTITIES::BEACHNPC, "npc"),
                "forestnpc" => array(TYPE_ENTITIES::FORESTNPC, "npc"),
                "desertnpc" => array(TYPE_ENTITIES::DESERTNPC, "npc"),
                "lavanpc" => array(TYPE_ENTITIES::LAVANPC, "npc"),
            );
        }
    }

    //根据TYPE_ENTITIES 获取到对应类型名字
    public static function getKindAsString($kind)
    {
        self::init();
        foreach (self::$kinds as $k => $kinditem) {
            if (self::$kinds[$k][0] == $kind) {
                return $k;
            }
        }
        return false;
    }

    public static function getKindFromString($kind)
    {
        self::init();
        if (!self::$kinds[$kind]) {
            return self::$kinds[$kind][0];
        }
        return false;
    }

    //根据entity类型获取 类型文字描述
    public static function getType($kind)
    {
        self::init();
        return self::$kinds[self::getKindAsString($kind)][1];
    }

    //根据entity类型获取类型文字描述以此判断是否为player
    public static function isPlayer($kind)
    {
        self::init();
        return self::getType($kind) == "player";
    }

    public static function isMob($kind)
    {
        self::init();
        return self::getType($kind) == "mob";
    }

    public static function isNpc($kind)
    {
        self::init();
        return self::getType($kind) == "npc";
    }

    public static function isCharacter($kind)
    {
        self::init();
        return self::isMob($kind) || self::isNpc($kind) || self::isPlayer($kind);
    }

    public static function isArmor($kind)
    {
        self::init();
        return self::getType($kind) == "armor";
    }

    public static function isWeapon($kind)
    {
        self::init();
        return self::getType($kind) == "weapon";
    }

    public static function isObject($kind)
    {
        self::init();
        return self::getType($kind) == "object";
    }

    public static function isChest($kind)
    {
        return $kind == TYPE_ENTITIES::CHEST;
    }

    public static function isItem($kind)
    {
        self::init();
        return self::isWeapon($kind)
        || self::isArmor($kind)
        || (self::isObject($kind) && !self::isChest($kind));
    }

    public static function isHealingItem($kind)
    {
        return $kind == TYPE_ENTITIES::FLASK || $kind == TYPE_ENTITIES::BURGER;
    }

    public static function isExpendableItem($kind)
    {
        return $kind == TYPE_ENTITIES::FIREPOTION || $kind == TYPE_ENTITIES::CAKE;
    }

    public static function forEachKind($callback)
    {
        self::init();
        foreach (self::$kinds as $k => $v) {
            $callback($v[0], $k);
        }
    }

    public static function forEachArmor($callback)
    {
        self::init();
        foreach (self::$kinds as $k => $v) {
            if (self::isArmor($v[0])) {
                $callback($v[0], $k);
            }
        }
    }

    public static function forEachMobOrNpcKind($callback)
    {
        self::init();
        foreach (self::$kinds as $k => $v) {
            if (self::isMob($v[0]) || self::isNpc($v[0])) {
                $callback($v[0], $k);
            }
        }
    }

    public static function forEachArmorKind($callback)
    {
        self::init();
        foreach (self::$kinds as $k => $v) {
            if (self::isArmor($v[0])) {
                $callback($v[0], $k);
            }
        }
    }

    public static function getRandomItemKind($item)
    {
        static $combineKind;
        if(!$combineKind)
        {
            $tmpArray = array_merge(TYPE_RANKEDARMORS::getRankedAromrs(),TYPE_RANKEDWEAPON::getRankedWepons());
            $forbidden = array_merge(TYPE_ENTITIES::SWORD1,TYPE_ENTITIES::CLOTHARMOR);
            $tmpArray = array_filter($tmpArray , function($value){
                if($value == TYPE_ENTITIES::SWORD1 ||$value ==  TYPE_ENTITIES::CLOTHARMOR))
                {
                    return true;
                }
                return false;
            });
            $combineKind = $tmpArray;
        }
        return array_rand($combineKind);
    }

}

class TYPE_RANKEDWEAPON
{
    public static $rankedWeapon;

    public static function init()
    {
        if (!self::$rankedWeapon) {
            self::$rankedWeapon = array(
                TYPE_ENTITIES::SWORD1,
                TYPE_ENTITIES::SWORD2,
                TYPE_ENTITIES::AXE,
                TYPE_ENTITIES::MORNINGSTAR,
                TYPE_ENTITIES::BLUESWORD,
                TYPE_ENTITIES::REDSWORD,
                TYPE_ENTITIES::GOLDENARMOR,
            );
        }
    }

    public static function getRankedWepons()
    {
        return self::$rankedWeapon;
    }

    public static function getWeaponRank($weaponkind)
    {
        return array_search(self::$rankedWeapon, $weaponkind);
    }
}

class TYPE_RANKEDARMORS
{
    public static $rankedArmors;

    public static function init()
    {
        if (!self::$rankedArmors) {
            self::$rankedArmors = array(
                TYPE_ENTITIES::CLOTHARMOR,
                TYPE_ENTITIES::LEATHERARMOR,
                TYPE_ENTITIES::MAILARMOR,
                TYPE_ENTITIES::PLATEARMOR,
                TYPE_ENTITIES::REDARMOR,
                TYPE_ENTITIES::GOLDENARMOR,
            );
        }
    }

    public static function getRankedAromrs()
    {
        return self::$rankedArmors;
    }

    public static function getArmorRanked($armorkind)
    {
        return array_search(self::$rankedArmors, $armorkind);
    }

}
