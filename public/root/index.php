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
            <p class="content__folder-title">Folders</p>
        </div>
        <div class="content__list">
            <?php
            $basePath = "./Files";
            $newBasePath = $basePath;
            $dirContent = scandir($newBasePath);
            foreach ($dirContent as $v) {
                $fileExtension = explode(".", $v);
                $sizeOfFile = get_folder_size($newBasePath . "/" . $v);
                $timeModified = date("F d Y", filemtime($newBasePath . "/" . $v));
                if (!is_file($newBasePath . "/" . $v)) {
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
                                        <p>$sizeOfFile</p>
                                        <p>$timeModified</p>
                                    </div>";
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

    <!-- File Upload Modal  -->
    <article class="modal__file" id="modal__file">
        <div class="modal__content-file" id="modal__content-file">
            <button id="button-close-file" class="modal__close-file">X</button>
            <form
                id="modal-form-file"
                method="post"
                enctype="multipart/form-data"
            >
                <div class="padding-1">
                    <label for="fileUpload">Title :</label>
                    <input
                        type="file"
                        id="file"
                        name="fileUpload"
                        value=""
                        required
                    />
                </div>
                <div class="padding-1">
                    <button id="cancel-modal-file" class="button--small">
                        Cancel
                    </button>
                    <button type="submit" class="button--small">Submit</button>
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