<?php
require_once("./defined/config.php");
require_once("./defined/def.php");

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
