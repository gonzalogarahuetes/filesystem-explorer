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