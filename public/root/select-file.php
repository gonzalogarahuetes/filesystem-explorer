<?php
require_once("../../config/app.php");

session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
require_once("./user_actions.php");

$file = $_GET["file"];

selectFile($file);

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
            <p class="content__folder-title"><?= $file ?></p>
        </div>
        <div class="content__list">
            <?php
            $basePath = "./Files" . "/" . $file;
            if (is_file($basePath)) {
                $fileExtension = explode(".", $basePath);
                // echo $fileExtension[2];
                $sizeOfFile = filesize($basePath);
                $timeModified = date("F d Y", filemtime($basePath));
                echo "
                                    <div class='display_folder'>
                                        <img class='fileIcon' src='./Icons/$fileExtension[2].svg'>
                                        <p class='folder1__element'>$file</p>
                                        <p>$sizeOfFile</p>
                                        <p>$timeModified</p>
                                    </div>";
            } else {
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
                                                <p class='folder1__element'>$v</p>
                                                <p>$sizeOfFile</p>
                                                <p>$timeModified</p>
                                            </div>";
                            }
                        }
                    }
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
    </section>

</main>
</body>

</html>