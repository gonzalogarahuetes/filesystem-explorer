<?php

function newFolder()
{
    session_start();

    $username = $_SESSION["username"];
    $folderName = $_POST["newFolder"];
    $path = "./" . $username . "_root" . "/" . $folderName;

    mkdir($path, 0777, true);

    header("Location: ./index.php");
}


function selectFile($file)
{
    session_start();

    if (is_dir($file)) {
        unset($_SESSION["fileInfo"]);
    } else {
        $explodeDot = explode(".", $file);
        $explodeSlash = explode("/", $file);
        $doubleExplode = explode("/", $explodeDot[count($explodeDot) - 2]);
        $type = $explodeDot[count($explodeDot) - 1];
        $shortName = $doubleExplode[count($doubleExplode) - 1];
        $name = $explodeSlash[count($explodeSlash) - 1];
        $bytes = filesize($file);
        $size = convertBytes($bytes);
        $modified = date("F d Y", filemtime($file));
        $created = date("F d Y", filectime($file));

        $fileInfo = array("name" => $name, "type" => $type, "size" => $size, "modified" => $modified, "created" => $created, "shortName" =>  $shortName);

        $_SESSION["fileInfo"] = $fileInfo;
    }
    header("Location: ./index.php");
}

function convertBytes($bytes)
{
    if ($bytes < 1024) {
        $size = $bytes . " bytes";
    } elseif (1024 <= $bytes && $bytes < 1048576) {
        $size = number_format($bytes / 1024, 2) . " Kb";
    } else {
        $size = number_format($bytes / 1048576, 2) . " Mb";
    }
    return $size;
}

function fileToTrash($file)
{
    session_start();

    $explodePath = explode("root", __DIR__);
    $explodeSlash = explode("/", $file);
    $fileName = $explodeSlash[count($explodeSlash) - 1];

    if (is_dir($file)) {
        foreach (glob($file) as $f) {
            fileToTrash($f);
        }
        $newPath = $explodePath[0] . "trash\\" . $fileName;
    } else {
        $folderName = $explodeSlash[count($explodeSlash) - 2];
        if ($folderName !== $_SESSION["username"]) {
            $newPath = $explodePath[0] . "trash\\" . $folderName . "\\" . $fileName;
        } else {
            $newPath = $explodePath[0] . "trash\\" . $fileName;
        }
    }
    rename($file, $newPath);
    header("Location: ./index.php");

    if (isset($_SESSION["fileInfo"])) unset($_SESSION["fileInfo"]);
}

function deleteFile($file)
{
    if (is_dir($file)) {
        foreach (glob($file) as $f) {
            deleteFile($f);
        }
        rmdir($file);
    } else {
        unlink($file);
    }

    session_start();

    header("Location: ./index.php");

    if (isset($_SESSION["fileInfo"])) unset($_SESSION["fileInfo"]);
}

function editFile($file, $newName)
{
    session_start();
    $explodeDot = explode(".", $file);
    $extension = "." . $explodeDot[count($explodeDot) - 1];

    $newCompleteName = "./" . $_SESSION["username"] . "_root/" . $newName . $extension;

    rename($file, $newCompleteName);

    $_SESSION["fileInfo"]["shortName"] = $newName;
    $_SESSION["fileInfo"]["name"] = $newName . $extension;
    header("Location: ./index.php");
}

function listFolderFiles($basePath) {
    $items = scandir($basePath);
    unset($items[array_search('.', $items, true)]);
    unset($items[array_search('..', $items, true)]);
    // prevent empty ordered elements
    if (count($items) < 1) return;
    foreach ($items as $item) {
        $fileExtension = explode(".", $item);
        if (is_dir($basePath . '/' . $item)) {
            echo "<div class='display_folder-title'><img class='fileIcon' src='./Icons/folder.svg'><p class='folder1__element'><a href='./select-file.php?file=$item' class='link'>$item</a></p></div>";
            // listFolderFiles($basePath.'/'.$item);
        } else {
            echo "<div class='display_folder-title'><img class='fileIcon' src='./Icons/$fileExtension[1].svg'><p class='folder1__element'><a href='./select-file.php?file=$item' class='link'>$item</a></p></div>";
        }
    }
}

function get_folder_size($folder) {
    $total_size = 0;
    if (is_file($folder)) $total_size = $total_size + filesize($folder);
    if (is_dir($folder)) {
        $files = scandir($folder);
            foreach ($files as $file) {
                if ($file === '.' or $file === '..') {
                    continue;
                } else {
                    $path = $folder . '/' . $file;
                    $total_size = $total_size + filesize($path);
                    get_folder_size($path);
                }
            }
        }
    return $total_size;
}