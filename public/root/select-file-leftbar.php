<?php
require_once("../../config/app.php");

session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
require_once("./user_actions.php");

$basePath = $_SESSION["username"] . "_root";

$file = $_GET["file"];

// get path to upload file and create files/folder inside certain folder
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
                <h3><a href='./index.php'>/<?= $basePath ?></a></h3>
                    /
                <?php
                    $path = $file;
                    displayPath($path);
                ?>
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
                <button class="guide_upload" id="btn__upload"><img class='fileIcon-small' src="../../assets/icons/cloudup.svg"></i></button>
            </div>
        </div>
        <div class="content__folder">
            <img class='fileIcon' src='./Icons/folder.svg'>
            <p class="content__folder-title"><a href='./index.php'>/<?= $basePath ?></a></p>
        </div>
        <div class="content__list">
            <?php
            $path = "./" . $file;
            listFolderDetails($path);
            ?>
        </div>
    </section>
    <section class="details">
        <?php
            $basePath = "./" . $file;
            displayDetails($basePath);
        ?>
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