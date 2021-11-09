<?php
require_once("../../config/app.php");

session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
require_once("./user_actions.php");

$basePath = $_SESSION["username"] . "_root";

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
                <h3><a href='./index.php'>/root</a></h3>
            </div>
            <?php
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
            $filePath = $basePath . "/" . $file;
            if (is_file($filePath)) {
                $fileExtension = explode(".", $filePath);
                // echo $fileExtension[2];
                $sizeOfFile = filesize($filePath);
                $timeModified = date("F d Y", filemtime($filePath));
                echo "
                                    <div class='display_folder'>
                                        <img class='fileIcon' src='./Icons/$fileExtension[2].svg'>
                                        <p class='folder1__element'>$file</p>
                                        <p>$sizeOfFile</p>
                                        <p>$timeModified</p>
                                    </div>";
            } else {
                $dirContent = scandir($filePath);
                foreach ($dirContent as $v) {
                    $fileExtension = explode(".", $v);
                    $sizeOfFile = get_folder_size($filePath . "/" . $v);
                    $timeModified = date("F d Y", filemtime($filePath . "/" . $v));
                    if (!is_file($filePath . "/" . $v)) {
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

            
            ?>
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
            <!-- <p>Type <span>PHP<span></p>
            <p>Size <span>500Kb<span></p>
            <p>Modified <span>12/01/2021<span></p>
            <p>Created <span>1/01/2021<span></p> -->
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
                action="./upload.php"
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
</body>

</html>