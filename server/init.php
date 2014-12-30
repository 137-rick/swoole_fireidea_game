<?php
//由于加载有先后顺序问题
//最后采用手动整理方式做的加载


//基础配置定义
require_once("./defined/config.php");
require_once("./classes/gametypes.php");

//基类
require_once("./classes/area.php");
require_once("./classes/entity.php");
require_once("./classes/item.php");


/*
function getIncludeFiles($path)
{
    $filelist = glob($path);
    foreach($filelist as $file)
    {
        include_once($file);
    }
}

getIncludeFiles("./classes/*.php");
getIncludeFiles("./defined/*.php");
*/