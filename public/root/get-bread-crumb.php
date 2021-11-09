<?php

function getBreadCrumb($path, $basePath)
{
    $pathArr = explode("/", $path);
    $currentPath = "";
    for ($i = 1; $i < count($pathArr); $i++) {
        $currentPath = $currentPath . "/" . $pathArr[$i];
        echo " / <a class='content__folder-title' href='./select-file.php?file=$basePath$currentPath'>$pathArr[$i]</a>";
    }
}
