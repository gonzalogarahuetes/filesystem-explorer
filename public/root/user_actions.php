<?php

function newFolder()
{
    $folderName = $_POST["newFolder"];
    $path = "./Files/$folderName";

    mkdir($path, 0777, true);

    header("Location: ./index.php");
}


function selectFile($file)
{
    session_start();

    $explodeDot = explode(".", $file);
    $explodeSlash = explode("/", $file);
    $type = $explodeDot[count($explodeDot) - 1];
    $name = $explodeSlash[count($explodeSlash) - 1];
    $bytes = filesize($file);
    $size = convertBytes($bytes);
    $modified = date("F d Y", filemtime($file));
    $created = date("F d Y", filectime($file));

    $fileInfo = array("name" => $name, "type" => $type, "size" => $size, "modified" => $modified, "created" => $created);

    $_SESSION["fileInfo"] = $fileInfo;

    header("Location: ./index.php");
}

function convertBytes($bytes)
{
    if ($bytes < 1024) {
        $size = $bytes . " bytes";
    } elseif (1024 <= $bytes && $bytes < 1048576) {
        $size = number_format($bytes / 1024, 2) . " Kb";
    } else {
        $size = number_format($bytes / 1048576, 2) . " Mb";;
    }
    return $size;
}

function deleteFile($file)
{
    session_start();
    unlink($file);
    header("Location: ./index.php");

    if (isset($_SESSION["fileInfo"])) unset($_SESSION["fileInfo"]);
}
