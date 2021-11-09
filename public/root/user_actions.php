<?php

function newFolder($realPath)
{
    session_start();

    $username = $_SESSION["username"];
    $folderName = $_POST["newFolder"];
    $path = "./$realPath/$folderName";

    mkdir($path, 0777, true);

    header("Location: ./index.php");
}


function selectFile($file)
{
    session_start();
    $_SESSION["showing-trash"] = false;
    $explodeSlash = explode("/", $file);
    $modified = date("F d Y", filemtime($file));
    $created = date("F d Y", filectime($file));

    if (is_dir($file)) {
        $size = convertBytes(folderSize($file));
        $dirName = $explodeSlash[count($explodeSlash) - 1];

        $fileInfo = array("path" => $file, "name" => $dirName, "type" => "Folder", "size" => $size, "modified" => $modified, "created" => $created, "shortName" =>  $dirName);
    } else {
        $fileInfo = getFileInfo($file);
    }
    $_SESSION["fileInfo"] = $fileInfo;
    header("Location: ./index.php");
}

function selectFileTrash($file)
{
    session_start();
    $explodeSlash = explode("/", $file);
    $modified = date("F d Y", filemtime($file));
    $created = date("F d Y", filectime($file));

    if (is_dir($file)) {
        $size = convertBytes(folderSize($file));
        $dirName = $explodeSlash[count($explodeSlash) - 1];

        $fileInfo = array("path" => $file, "name" => $dirName, "type" => "Folder", "size" => $size, "modified" => $modified, "created" => $created, "shortName" =>  $dirName);
    } else {
        $fileInfo = getFileInfo($file);
    }
    $_SESSION["fileInfoTrash"] = $fileInfo;
    header("Location: ./index.php");
}

function getFileInfo($file)
{
    $explodeDot = explode(".", $file);
    $explodeSlash = explode("/", $file);
    $doubleExplode = explode("/", $explodeDot[count($explodeDot) - 2]);
    $modified = date("F d Y", filemtime($file));
    $created = date("F d Y", filectime($file));
    $type = $explodeDot[count($explodeDot) - 1];
    $shortName = $doubleExplode[count($doubleExplode) - 1];
    $name = $explodeSlash[count($explodeSlash) - 1];
    $bytes = filesize($file);
    $size = convertBytes($bytes);

    $fileInfo = array("path" => $file, "name" => $name, "type" => $type, "size" => $size, "modified" => $modified, "created" => $created, "shortName" =>  $shortName);

    return $fileInfo;
}

function folderSize($dir)
{
    $size = 0;

    foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }

    return $size;
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
        // $folderName = dirname($file);
        // $arrPath = explode($_SESSION["username"] . "_root/", $folderName);
        // if ($folderName !== $_SESSION["username"] . "_root") {
        //     $newPath = $explodePath[0] . "trash" . "\\" . $arrPath[count($arrPath) - 1] . "\\" . $fileName;
        // } else {
        //     $newPath = $explodePath[0] . "trash" . "\\" . $fileName;
        // }
        $newPath = $explodePath[0] . "trash" . "\\" . $fileName;
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

function editFile($file, $newName,)
{
    session_start();
    $explodeDot = explode(".", $file);
    $extension = "." . $explodeDot[count($explodeDot) - 1];
    $wholePath = dirname($file);
    $explodeRoot = explode($_SESSION["username"] . "_root/", $wholePath);

    $newCompleteName = "./" . $_SESSION["username"] . "_root/" . $explodeRoot[1] . "/" . $newName . $extension;

    rename($file, $newCompleteName);

    $_SESSION["fileInfo"] = getFileInfo($newCompleteName);

    $_SESSION["fileInfo"]["path"] = $_SESSION["username"] . "_root/" . $explodeRoot[1] . "/" . $newName . $extension;
    $_SESSION["fileInfo"]["shortName"] = $newName;
    $_SESSION["fileInfo"]["name"] = $newName . $extension;
    header("Location: ./index.php");
}

function listFolderFiles($basePath)
{
    $items = scandir($basePath);
    unset($items[array_search('.', $items, true)]);
    unset($items[array_search('..', $items, true)]);
    // prevent empty ordered elements
    if (count($items) < 1) return;
    foreach ($items as $item) {
        $fileExtension = explode(".", $item);
        $fileActualExt = strtolower(end($fileExtension));
        if (is_dir($basePath . '/' . $item)) {
            echo "<div class='display_folder-title'><img class='fileIcon' src='./Icons/folder.svg'><p class='folder1__element'><a class='main__anchor' href='./select-file.php?file=$basePath/$item' class='link'>$item</a></p></div>";
        } else {
            echo "<div class='display_folder-title'><img class='fileIcon' src='./Icons/$fileActualExt.svg'><p class='folder1__element'><a class='main__anchor' href='./select-file.php?file=$basePath/$item' class='link'>$item</a></p></div>";
        }
    }
}

function displayInfoParentFolder($basePath)
{
    if (is_dir($basePath)) {
        $dirContent = scandir($basePath);
        foreach ($dirContent as $v) {
            $fileExtension = explode(".", $v);
            $fileActualExt = strtolower(end($fileExtension));
            $sizeOfFile = get_folder_size($basePath . "/" . $v);
            $timeModified = date("F d Y", filemtime($basePath . "/" . $v));
            if (is_dir($basePath . "/" . $v)) {
                if ($v !== '.' && $v !== '..') {
                    echo "
                                    <div class='display_folder'>
                                        <img class='fileIcon' src='./Icons/folder.svg'>
                                        <p class='folder1__element'><a class='main__anchor' href='./select-file.php?file=$basePath/$v'>$v</a></p>
                                        <p>$sizeOfFile</p>
                                        <p>$timeModified</p>
                                    </div>";
                }
            } else {
                echo "
                                <div class='display_folder'>
                                    <img class='fileIcon' src='./Icons/$fileActualExt.svg'>
                                    <p class='folder1__element'><a class='main__anchor' href='./select-file.php?file=$basePath/$v'>$v</a></p>
                                    <p>$sizeOfFile</p>
                                    <p>$timeModified</p>
                                </div>";
            }
        }
    } else {
        $arrayPath = explode("/", $basePath);
        $fileName = $arrayPath[count($arrayPath) - 1];
        $fileExtension = explode(".", $basePath);
        $fileActualExt = strtolower(end($fileExtension));
        $sizeOfFile = convertBytes(filesize($basePath));
        $timeModified = date("F d Y", filemtime($basePath));
        echo "
        <div class='display_folder'>
            <img class='fileIcon' src='./Icons/$fileActualExt.svg'>
            <p class='folder1__element'><a class='main__anchor' href='./select-file.php?file=$basePath'>$fileName</a></p>
            <p>$sizeOfFile</p>
            <p>$timeModified</p>
        </div>";
    }
}
function listFolderDetails($basePath)
{
    if (is_file($basePath)) {
        $fileExtension = explode(".", $basePath);
        $fileActualExt = strtolower(end($fileExtension));
        $sizeOfFile = filesize($basePath);
        $timeModified = date("F d Y", filemtime($basePath));
        $name = basename($basePath);
        echo "
                            <div class='display_folder'>
                                <img class='fileIcon' src='./Icons/$fileActualExt.svg'>
                                <p class='folder1__element'><a class='main__anchor' href='./select-file-rightbar.php?getFile=$basePath' class='link'>$name</a></p>
                                <p>$sizeOfFile</p>
                                <p>$timeModified</p>
                            </div>";
    }
    if (is_dir($basePath)) {
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
                                        <p class='folder1__element'><a class='main__anchor' href='./select-file-rightbar.php?getFile=$basePath/$v' class='link'>$v</a></p>
                                        <p>$sizeOfFile</p>
                                        <p>$timeModified</p>
                                    </div>";
                    }
                }
            }
            if (is_file($basePath . "/" . $v)) {
                echo "
                                    <div class='display_folder'>
                                    <img class='fileIcon' src='./Icons/$fileActualExt.svg'>
                                        <p class='folder1__element'><a class='main__anchor' href='./select-file-rightbar.php?getFile=$basePath/$v' class='link'>$v</a></p>
                                        <p>$sizeOfFile</p>
                                        <p>$timeModified</p>
                                    </div>";
            }
        }
    }
}

function get_folder_size($folder)
{
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
function upload($realPath)
{
    if (isset($_POST['submit'])) {
        $file = $_FILES['fileUpload'];

        $fileName = $_FILES['fileUpload']['name'];
        $fileType = $_FILES['fileUpload']['type'];
        $fileTempDir = $_FILES['fileUpload']['tmp_name'];
        $fileSize = $_FILES['fileUpload']['size'];
        $fileError = $_FILES['fileUpload']['error'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowedFileType = array('jpg', 'jpeg', 'png', 'pdf', 'odt', 'txt', 'svg', 'mp3', 'mp4', 'csv');

        if (in_array($fileActualExt, $allowedFileType)) {
            if ($fileError === 0) {
                if ($fileSize < 1000000) {
                    $fileNewName = uniqid('', true) . '.' . $fileActualExt;
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

// Display Path 
function displayPath($getFile)
{
    $arrayPath = array();
    $breakFullPath = explode("/", $getFile);
    $currentIndex = "";
    for ($i = 0; $i < count($breakFullPath); $i++) {
        $currentIndex = $currentIndex . $breakFullPath[$i] . "/";
        array_push($arrayPath, $currentIndex);
    }
    // print_r($arrayPath);
    $arrayActual = array_slice($arrayPath, 2);

    foreach ($arrayActual as $index => $c) {
        $n = basename($c);
        echo "<a href='./select-file-rightbar.php?getFile=$arrayActual[$index]'>" . $n . "/" . " " . "</a>";
    }
}

// Display details of folder/file on right bar
function displayDetails($basePath)
{
    if (is_file($basePath)) {
        $fileExtension = explode(".", $basePath);
        $fileActualExt = strtolower(end($fileExtension));

        $getFileName = explode("/", $basePath);
        $fileActualName = end($getFileName);

        $sizeOfFile = filesize($basePath);

        $timeModified = date("F d Y", filemtime($basePath));
        echo "
            <div class='details__header'>
                <img class='fileIcon' src='./Icons/$fileActualExt.svg'>
                <p classname='details__name'>$fileActualName</p>
                <button type='button' data-open='modal1' class='details__btn--edit'><img class='fileIcon-medium' src='../../assets/icons/edit.svg'></button>
                <button class='details__btn--delete' onclick='location.href=\"./delete_file.php?file=$basePath/$fileActualName\"'><img class='fileIcon-medium' src='../../assets/icons/delete.svg' onclick='location.href=\"./delete_file.php?file=$basePath/$fileActualName\"'></button>
            </div>
            <div class='details__content'>
                <div class='details__flex'>
                    <p><strong>Type:</strong></p>
                    <p>$fileActualExt</p>
                </div>
                <div class='details__flex'>
                    <p><strong>Size:</strong></p>
                    <p>$sizeOfFile</p>
                </div>
                <div class='details__flex'>
                    <p><strong>Modified:</strong></p>
                    <p>$timeModified</p>
                </div>
                <div class='details__flex'>
                    <p><strong>Created:</strong></p>
                    <p>NN</p>
                </div>
            </div>";
    }
    if (is_dir($basePath)) {
        $getFileName = explode("/", $basePath);
        $fileActualName = end($getFileName);

        $sizeOfFile = get_folder_size($basePath);
        $timeModified = date("F d Y", filemtime($basePath));
        echo "
            <div class='details__header'>
                <img class='fileIcon' src='./Icons/folder.svg'>
                <p classname='details__name'>$fileActualName</p>
                <button type='button' data-open='modal1' class='details__btn--edit'><img class='fileIcon-medium' src='../../assets/icons/edit.svg'></button>
                <button class='details__btn--delete' onclick='location.href=\"./delete_file.php?file=$basePath/$fileActualName\"'><img class='fileIcon-medium' src='../../assets/icons/delete.svg' onclick='location.href=\"./delete_file.php?file=$basePath/$fileActualName\"'></button>
            </div>
            <div class='details__content'>
                <div class='details__flex'>
                    <p><strong>Type:</strong></p>
                    <p>Folder</p>
                </div>
                <div class='details__flex'>
                    <p><strong>Size:</strong></p>
                    <p>$sizeOfFile</p>
                </div>
                <div class='details__flex'>
                    <p><strong>Modified:</strong></p>
                    <p>$timeModified</p>
                </div>
                <div class='details__flex'>
                    <p><strong>Created:</strong></p>
                    <p>NN</p>
                </div>
            </div>";
    }
}

function showTrash()
{

    $_SESSION["showing-trash"] = true;

    $trashContent = scandir("../trash");
    foreach ($trashContent as $v) {
        $fileExtension = explode(".", $v);
        $fileActualExt = strtolower(end($fileExtension));
        $sizeOfFile = get_folder_size("../trash/" . $v);
        $timeModified = date("F d Y", filemtime("../trash/" . $v));
        if (is_dir("../trash/" . $v)) {
            if ($v !== '.' && $v !== '..') {
                echo "
                                    <div class='display_folder'>
                                        <img class='fileIcon' src='./Icons/folder.svg'>
                                        <p class='folder1__element'><a class='main__anchor' href='./select-file--trash.php?file=../trash/$v'>$v</a></p>
                                        <p>$sizeOfFile</p>
                                        <p>$timeModified</p>
                                    </div>";
            }
        } else {
            echo "
                                <div class='display_folder'>
                                    <img class='fileIcon' src='./Icons/$fileActualExt.svg'>
                                    <p class='folder1__element'><a class='main__anchor' href='./select-file--trash.php?file=..trash/$v'>$v</a></p>
                                    <p>$sizeOfFile</p>
                                    <p>$timeModified</p>
                                </div>";
        }
    }
}
