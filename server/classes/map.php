<?php

class Map
{
    public $mapWidth;
    public $mapHeight;
    public $mapCollisions;
    public $mobAreas;
    public $chestAreas;
    public $staticChests;
    public $staticEntities;

    public $zoneWidth;
    public $zoneHeight;
    public $groupWidth;
    public $groupHeight;

    public $mapGrid;
    public $connectedGroups;

    public $checkpoints;
    public $startingAreas;


    function __construct()
    {
        //load map from js json format file
        $content = file_get_contents("../defined/world_map.js");
        if ($content) {
            //decode
            $mapinfo = json_decode(trim($content), true);
            if ($mapinfo) {
                // now geted info
                $this->mapWidth = $mapinfo["width"];
                $this->mapHeight = $mapinfo["height"];

                $this->mapCollisions = $mapinfo["collisions"];
                $this->mobAreas = $mapinfo["roamingAreas"];
                $this->chestAreas = $mapinfo["chestAreas"];
                $this->staticChests = $mapinfo["staticChests"];
                $this->staticEntities = $mapinfo["staticEntities"];

                $this->zoneHeight = 28;
                $this->zoneWidth = 12;
                $this->groupHeight = floor($this->mapWidth / $this->zoneWidth);
                $this->groupWidth = floor($this->zoneHeight / $this->zoneHeight);


                $this->initConnectedGroups($mapinfo["doors"]);
                $this->initCheckpoints($mapinfo["checkpoints"]);

                //ready: function(f) {
                //    this.ready_func = f;
                //},

            } else {
                throw new Exception("load map error: decode error.", "100001");
            }
        } else {
            throw new Exception("load map error:load file error.", "100002");
        }

    }

    //index to grid x y
    public function tileIndexToGridPosition($tileNum)
    {
        //$x = 0;
        //$y = 0;

        if ($tileNum == 0) {
            $x = 0;
        } else {
            $x = ($tileNum % $this->mapWidth == 0) ? $this->mapWidth - 1 : ($tileNum % $this->mapWidth - 1);
        }

        $y = floor(($tileNum - 1) / $this->mapWidth);
        return array("x" => $x, "y" => $y);
    }

    //x y to index
    public function GridPositionToTileIndex($x, $y)
    {
        return ($y * $this->mapWidth) + $x + 1;
    }

    //根据物品生成是否可走图
    public function generateCollisionGrid()
    {
        $this->mapGrid = array();
        $tileIndex = 0;
        for ($i = 0; $i < $this->mapHeight; $i++) {
            $this->mapGrid[$i] = array();
            for ($j = 0; $j < $this->mapWidth; $j++) {
                if ($this->mapCollisions[$tileIndex]) {
                    $this->mapGrid[$i][$j] = 1;
                } else {
                    $this->mapGrid[$i][$j] = 0;
                }
                $tileIndex += 1;
            }
        }
    }

    //是否在地图内
    public function isOutOfBounds($x, $y)
    {
        return $x <= 0 || $x >= $this->mapWidth || $y <= 0 || $y >= $this->mapHeight;
    }

    //是否是杂物
    public function isColliding($x, $y)
    {
        if ($this->isOutOfBounds($x, $y)) {
            return false;
        }
        return $this->mapGrid[$x][$y] === 1;
    }

    //根据组id获取组对应坐标
    public function  GroupIdToGroupPosition($id)
    {
        $posArray = explode("-", $id);
        return array((int)($posArray[0]), (int)($posArray[1]));
    }

    //遍历组内对象
    public function forEachGroup($callback)
    {
        for ($x = 0; $x < $this->mapWidth; $x++) {
            for ($y = 0; $y < $this->mapHeight; $y++) {
                $callback($x . "-" . $y);
            }
        }
    }

    //通过坐标判断组id
    public function getGroupIdFromPosition($x, $y)
    {
        $gx = floor(($x - 1) / $this->zoneWidth);
        $gy = floor(($y - 1) / $this->zoneHeight);
        return $gx . "-" . $gy;
    }

    //获取临近图组信息
    public function getAdjacentGroupPositions($id)
    {
        $position = $this->GroupIdToGroupPosition($id);
        $x = $position[0];
        $y = $position[1];

        $list = array(
            array($x - 1, $y - 1), array($x, $y - 1), array($x + 1, $y - 1),
            array($x - 1, $y), array($x, $y), array($x + 1, $y),
            array($x - 1, $y + 1), array($x - 1, $y + 1), array($x + 1, $y + 1),
        );
        foreach ($this->connectedGroups[$id] as $cgroup) {
            foreach ($list as $k) {
                $isdiff = array_diff($k, $cgroup);
                if (empty($isdiff)) {
                    $list[] = $cgroup;
                }
            }
        }

        //拿到所有不符合条件的对象
        $result = array();

        foreach ($list as $l) {
            //判断超出当前组的坐标列表
            if (!($l[0] < 0 || $l[1] < 0 || $l[0] >= $this->groupWidth || $l[1] >= $this->groupHeight)) {
                $result[] = $l;
            }
        }
        return $result;
    }

    //遍历临近组
    public function forEachAdjacentGroup($groupid, $callback)
    {
        if ($groupid) {
            $grouppos = $this->getAdjacentGroupPositions($groupid);
            foreach ($grouppos as $g) {
                $callback($g);
            }
        }
    }

    public function initConnectedGroups($doors)
    {
        $this->connectedGroups = array();
        foreach ($doors as $door) {
            $groupid = $this->getGroupIdFromPosition($door["x"], $door["y"]);
            $connectedGroupId = $this->getGroupIdFromPosition($door["tx"], $door["ty"]);
            $connectedPosition = $this->GroupIdToGroupPosition($connectedGroupId);

            if (!empty($this->connectedGroups[$groupid])) {
                $this->connectedGroups[$groupid][] = $connectedPosition;
            } else {
                $this->connectedGroups[$groupid] = array();
                $this->connectedGroups[$groupid][] = $connectedPosition;
            }
        }
    }

    //check point 区域
    public function initCheckpoints($cpList)
    {
        //checkpoint 区域列表
        $this->checkpoints = array();
        //开始区域
        $this->startingAreas = array();

        foreach ($cpList as $cp) {
            $checkpoint = new checkPoint($cp["id"], $cp["x"], $cp["y"], $cp["w"], $cp["h"]);
            $this->checkpoints[$checkpoint->id] = $checkpoint;
            //是否有start 标志，有那么挂掉后在这里复活
            if ($cp["s"] == 1) {
                $this->startingAreas[] = $checkpoint;
            }
        }
    }

    public function getCheckpoint($id)
    {
        return $this->checkpoints[$id];
    }

    public function getRandomStartingPosition()
    {
        $nbAreas = count($this->startingAreas);
        $i = mt_rand(0, $nbAreas - 1);
        $area = $this->startingAreas[$i];
        //这里存的是checkpoint
        return $area->getRandomPosition();
    }
    /*
     * 这里没有实施 pos 对象，直接用了数组
    var pos = function(x, y) {
        return { x: x, y: y };
    };

    var equalPositions = function(pos1, pos2) {
        return pos1.x === pos2.x && pos2.y === pos2.y;
    };
     */


}
