<?php
require_once("../../config/app.php");
session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
require_once("./user_actions.php");

$getFile = $_GET["getFile"];

$fullPath = $_SERVER['REQUEST_URI'];
$explodePath = explode('=', $fullPath);
$realPath = end($explodePath);

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
        <form 
            action=<?= "./new_folder.php?realPath=$realPath" ?>
            method="post" 
            class="new-"
        >
            <input type="text" name="newFolder" class="explorer__new">
            <button type="submit" class="new-folder"> New Folder</button>
        </form>
        <div class="explorer__folders">
            <div class="explorer__folders-root">
                <img class='fileIcon' src='./Icons/folder.svg'>
                <h3><a href='./index.php'>/Files</a></h3>
            </div>
            <?php
            $basePath = "./Files";
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
                <button class="guide_upload" id="btn__upload"><img class='fileIcon-small' src="../../assets/icons/cloudup.svg"></i></button>
            </div>
        </div>
        <div class="content__folder">
            <img class='fileIcon' src='./Icons/folder.svg'>
            <p class="content__folder-title">
                <a href="<?Php echo create_url("/");?>">Files</a>
                    /
                <?php
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
                ?>
            </p>
        </div>
        <div class="content__list">
            <?php
            $basePath = $getFile;
            if (is_file($basePath)) {
                $fileExtension = explode(".", $basePath);
                $fileActualExt = strtolower(end($fileExtension));
                // echo $fileExtension[2];
                $sizeOfFile = filesize($basePath);
                $timeModified = date("F d Y", filemtime($basePath));
                echo "
                                    <div class='display_folder'>
                                        <img class='fileIcon' src='./Icons/$fileActualExt.svg'>
                                        <p class='folder1__element'><a href='./select-file-rightbar.php?getFile=$basePath/$getFile' class='link'>$getFile</a></p>
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
                                                <p class='folder1__element'><a href='./select-file-rightbar.php?getFile=$basePath/$v' class='link'>$v</a></p>
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
                                                <p class='folder1__element'><a href='./select-file-rightbar.php?getFile=$basePath/$v' class='link'>$v</a></p>
                                                <p>$sizeOfFile</p>
                                                <p>$timeModified</p>
                                            </div>";

                    }
                }
            }

            
            ?>
        </div>
    </section>
    <section class="details">
        <div class="details__title">
            <i></i>
            <p>
                <?php
                    $fileExplode = explode("/", $getFile);
                    $fileActualName = strtolower(end($fileExplode));
                    echo $fileActualName;
                ?>
            </p>
            <button class="details__btn--edit"><img class='fileIcon-medium' src="../../assets/icons/edit.svg"></button>
            <button class="details__btn--delete"><img class='fileIcon-medium' src="../../assets/icons/delete.svg"></button>
        </div>
        <div class="details__content">
            <?php
                $basePath = $getFile;
                if (is_file($basePath)) {
                    $fileExtension = explode(".", $basePath);
                    $fileActualExt = strtolower(end($fileExtension));
                    $sizeOfFile = filesize($basePath);
                    $timeModified = date("F d Y", filemtime($basePath));
                    echo "
                                        <div'>
                                            <p>Type: $fileActualExt<p>
                                            <p>Name: $fileActualName</a></p>
                                            <p>Size: $sizeOfFile</p>
                                            <p>Modified: $timeModified</p>
                                        </div>";
                } 
                if (is_dir($basePath)) {
                    $sizeOfFile = get_folder_size($basePath . "/" . $v);
                    $timeModified = date("F d Y", filemtime($basePath . "/" . $v));
                    echo "
                                <div'>
                                <p>Type: folder<p>
                                <p>Name: $fileActualName</a></p>
                                <p>Size: $sizeOfFile</p>
                                <p>Modified: $timeModified</p>
                            </div>";
                } 
            ?>
        </div>
    </section>

    <!-- File Upload Modal  -->
    <article class="modal__file" id="modal__file">
        <div class="modal__content-file" id="modal__content-file">
            <button id="button-close-file" class="modal__close-file">X</button>
            <form
                id="modal-form-file"
                method="post"
                enctype="multipart/form-data"
                action=<?= "./upload.php?realPath=$realPath" ?>
            >
                <div class="padding-1">
                    <label for="fileUpload">Title :</label>
                    <input
                        type="file"
                        id="fileUpload"
                        name="fileUpload"
                        value=""
                        required
                    />
                </div>
                <div class="padding-1">
                    <button id="cancel-modal-file" class="button--small">
                        Cancel
                    </button>
                    <button type="submit" name="submit" class="button--small">Submit</button>
                </div>
                </form>
        </div>
    </article><!-- File Modal  -->
</main>
<script>
    <?php
        require_once(ROOT_PATH . "assets/js/functions.js");
    ?>
</script>
</body>

</html>