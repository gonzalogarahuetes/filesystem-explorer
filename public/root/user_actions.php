<?php

function newFolder($realPath)
{

    $folderName = $_POST["newFolder"];
    $path = $realPath . "/" . $folderName;

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

function editFile($file, $newName)
{
    session_start();
    $explodeDot = explode(".", $file);
    $extension = "." . $explodeDot[count($explodeDot) - 1];

    $newCompleteName = "./Files/" . $newName . $extension;

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
        $fileActualExt = strtolower(end($fileExtension));
        if (is_dir($basePath . '/' . $item)) {
            echo "<div class='display_folder-title'><img class='fileIcon' src='./Icons/folder.svg'><p class='folder1__element'><a href='./select-file-leftbar.php?file=$basePath/$item' class='link'>$item</a></p></div>";
            // listFolderFiles($basePath.'/'.$item);
        } else {
            echo "<div class='display_folder-title'><img class='fileIcon' src='./Icons/$fileActualExt.svg'><p class='folder1__element'><a href='./select-file-leftbar.php?file=$basePath/$item' class='link'>$item</a></p></div>";
        }
    }
}

function displayInfoParentFolder($basePath) {
    $dirContent = scandir($basePath);
        foreach ($dirContent as $v) {
            $fileExtension = explode(".", $v);
            $fileActualExt = strtolower(end($fileExtension));
            $sizeOfFile = get_folder_size($basePath . "/" . $v);
            $timeModified = date("F d Y", filemtime($basePath . "/" . $v));
            if (!is_file($basePath . "/" . $v)) {
                if (!($v == '.')) {
                    if (!($v == '..')) {
                        echo "
                                    <div class='display_folder'>
                                        <img class='fileIcon' src='./Icons/folder.svg'>
                                        <p class='folder1__element'><a href='./select-file.php?file=$basePath/$v'>$v</a></p>
                                        <p>$sizeOfFile</p>
                                        <p>$timeModified</p>
                                    </div>";
                    }
                }
            } else {
                echo "
                                <div class='display_folder'>
                                    <img class='fileIcon' src='./Icons/$fileActualExt.svg'>
                                    <p class='folder1__element'><a href='./select-file.php?file=$basePath/$v'>$v</a></p>
                                    <p>$sizeOfFile</p>
                                    <p>$timeModified</p>
                                </div>";
            }
        }
}

function get_folder_size($folder) {
    $total_size = 0;
    if (is_file($folder)) {
        $total_size = $total_size + filesize($folder);
    }
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

// Upload files
function upload($realPath) {
    if (isset($_POST['submit'])) {
        $file = $_FILES['fileUpload'];

        $fileName = $_FILES['fileUpload']['name'];
        $fileType = $_FILES['fileUpload']['type'];
        $fileTempDir = $_FILES['fileUpload']['tmp_name'];
        $fileSize = $_FILES['fileUpload']['size'];
        $fileError = $_FILES['fileUpload']['error'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowedFileType = array('jpg', 'jpeg', 'png', 'pdf', 'odt', 'txt', 'svg' , 'mp3', 'mp4', 'csv');

        if (in_array($fileActualExt, $allowedFileType)) {
            if ($fileError === 0) {
                if($fileSize < 1000000) {
                    $fileNewName = uniqid('', true).'.'.$fileActualExt;
                    $fileDestination = $realPath . "/" . $fileNewName;
                    move_uploaded_file($fileTempDir, $fileDestination);
                    header('Location: ./index.php?uploadsuccess');
                } else {
                    echo "Error while uploading file / file size is too big";
                }
            } else {
                echo "Error while uploading file";
            }
        } else {
            echo "Can not upload file of this type";
        }
    }
}


// Functions regarding path
function create_url($path, $arguments=[]) {
    $url = BASE_PATH;
    if ($path != "/") {
        $url = $url . $path;
    }
    if (count($arguments) > 0) {
        $url = $url . "?";
        foreach ($arguments as $key => $value) {
            $url = $url . $key . "=" . $value;
        }
    }
    return $url;
}