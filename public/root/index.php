<?php
require_once("../../config/app.php");

session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
require_once("./user_actions.php");

if (isset($_SESSION["fileInfo"])) {
    $type = $_SESSION["fileInfo"]["type"];
    $size = $_SESSION["fileInfo"]["size"];
    $modified = $_SESSION["fileInfo"]["modified"];
    $created = $_SESSION["fileInfo"]["created"];
}

$title = "Index";
include(ROOT_PATH . "inc/_head.php");
?>
<header class="header">
    <img class='fileIcon-large' src="../../assets/icons/bug.svg">
    <input type="search" class="header__search" placeholder="Search">
    <p class="header_welcome">Welcome, <?= $_SESSION["username"] ?></p>
    <a href="../logout.php" class="log_out">Logout</a>
</header>
<main class="main">
    <section class="explorer">
        <form action="./new_folder.php" method="post" class="new-">
            <input type="text" name="newFolder" class="explorer__new">
            <button type="submit" class="new-folder"> New Folder</button>
        </form>
        <div class="explorer__folders">
            <div class="explorer__folders-root">
                <img class='fileIcon' src='./Icons/folder.svg'>
                <h3>/root</h3>
            </div>
            <?php
            $basePath = "./Files";
            function listFolderFiles($basePath)
            {
                $items = scandir($basePath);
                unset($items[array_search('.', $items, true)]);
                unset($items[array_search('..', $items, true)]);
                // prevent empty ordered elements
                if (count($items) < 1)
                    return;
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
            listFolderFiles($basePath);
            ?>
        </div>
    </section>
    <section class="content">
        <div class="content__guide">
            <p class="guide__p guide__p-left">Name</p>
            <div class="guide__p-right">
                <p class="guide__p">Size</p>
                <p class="guide__p">Modified</p>
                <button class="guide_upload"><img class='fileIcon-small' src="../../assets/icons/cloudup.svg"></i></button>
            </div>
        </div>
        <div class="content__folder">
            <img class='fileIcon' src='./Icons/folder.svg'>
            <p class="content__folder-title">Others</p>
        </div>
        <div class="content__list">
            <?php
            $basePath = "./Files";
            $dirContent = scandir($basePath);
            foreach ($dirContent as $v) {
                $fileExtension = explode(".", $v);
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
                                        <img class='fileIcon' src='./Icons/$fileExtension[1].svg'>
                                        <p class='folder1__element'><a href='./select-file.php?file=$basePath/$v'>$v</a></p>
                                        <p>&times;</p>
                                    </div>";
                }
            }

            function get_folder_size($folder)
            {
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
            ?>
        </div>



        <button id="btn-show">Open</button>
        <div id="modal" class="modal">
            <span class="close" id="btn-hidde">&times;</span>
                <?php
                    switch($type){
                        case "mp4":
                        case "mp3":
                            echo "
                                <video controls class='modal-content'>
                                    <source src='.\Files\video\movie.mp4' type='video/mp4'>
                                </video>
                                ";
                                break;

                        case "jpg":
                        case "png":
                            echo "
                                <img src='img_girl.jpg' alt='' class='modal-content'>
                            ";
                            break;

                        case "ogg":
                            echo "
                                <audio controls>
                                    <source src='horse.ogg' type='audio/ogg' class='modal-content'>
                                </audio>
                            ";
                            break;

                        case "zip":
                            echo "";
                            break;

                        case "pdf":
                            echo "";
                            break;

                        case "csv":
                            echo "";
                            break;

                        case "doc":
                            echo "";
                            break;

                        case "exe":
                            echo "";
                            break;

                        case "odt":
                            echo "";
                            break;

                        case "php":
                            echo "";
                            break;

                        case "ppt":
                            echo "";
                            break;

                        case "rar":
                            echo "";
                            break;

                        case "svg":
                            echo "";
                            break;

                        case "txt":
                            echo "";
                            break;

                        default:
                            echo "
                                <p>You can't show this file</p>
                                <button>Download</button>
                            ";
                    }
                
                echo ($type);
                echo ($basePath . "/" . $v . "." . $type);
                
                
                ?>
            <video controls class="modal-content">
                <source src='.\Files\movie.mp4' type='video/mp4'>
            </video>
        </div>



    </section>
    <section class="details">
        <div class="details__title">
            <i></i>
            <p>Title</p>
            <button class="details__btn--edit"><img class='fileIcon-medium' src="../../assets/icons/edit.svg"></button>
            <button class="details__btn--delete"><img class='fileIcon-medium' src="../../assets/icons/delete.svg"></button>
        </div>
        <div class="details__content">
            <p>Type</p>
            <p><?= $type ? $type : "" ?></p>
            <p>Size</p>
            <p><?= $size ? $size : "" ?></p>
            <p>Modified</p>
            <p><?= $modified ? $modified : "" ?></p>
            <p>Created</p>
            <p><?= $created ? $created : "" ?></p>
        </div>
    </section>
</main>
</body>

</html>