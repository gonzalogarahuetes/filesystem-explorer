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
    $name = $file;
    $explode = explode(".", $file);
    $type = $explode[count($explode) - 1];
    $size = filesize($file);
    $modified = filemtime($file);
    $created = filectime($file);

    $fileInfo = array("name" => $name, "type" => $type, "size" => $size, "modified" => $modified, "created" => $created);

    $_SESSION["fileInfo"] = $fileInfo;

    header("Location: ./index.php");
}
