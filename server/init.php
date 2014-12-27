<?php
require_once("./defined/config.php");
require_once("./defined/def.php");

function __autoload($classname)
{
    $classpath = strtolower( "./class/" . $classname . ".php");
    if (file_exists($classpath)) {
        require_once($classpath);
    }
}