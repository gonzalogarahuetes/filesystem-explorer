<?php
require_once("../../config/app.php");
require_once("./get-bread-crumb.php");

require_once("./visualice-files.php");

session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
require_once("./user_actions.php");

if (isset($_SESSION["fileInfo"])) {
    $currentUserPath = $_SESSION["fileInfo"]["path"];
    $name = $_SESSION["fileInfo"]["name"];
    $type = strtoupper($_SESSION["fileInfo"]["type"]);
    $size = $_SESSION["fileInfo"]["size"];
    $modified = $_SESSION["fileInfo"]["modified"];
    $created = $_SESSION["fileInfo"]["created"];
    $shortName = $_SESSION["fileInfo"]["shortName"];
}

$basePath = $_SESSION["username"] . "_root";
$realPath = $basePath;

$title = "Index";
include(ROOT_PATH . "inc/_head.php");
?>
<header class="header">
    <img class='fileIcon-large' src="../../assets/icons/bug.svg">
    <input type="search" class="header__search" placeholder="Search">
    <p class="header_welcome">Welcome, <?= $_SESSION["username"] ?></p>
    <a href="../logout.php" class="header__logout">Logout</a>
</header>
<main class="main">
    <section class="explorer">
        <form action=<?= isset($currentUserPath) ? "./new_folder.php?realPath=$currentUserPath" : "./new_folder.php?realPath=$basePath" ?> method="post" class="new-">
            <input type="text" name="newFolder" class="explorer__new">
            <button type="submit" class="new-folder"> New Folder</button>
        </form>
        <div class="explorer__folders">
            <div class="explorer__folders-root">
                <img class='fileIcon' src='./Icons/folder.svg'>
                <h3><a class="main__anchor" href='index.php'>/<?= $basePath ?></a></h3>
            </div>
            <?php
            listFolderFiles($basePath);
            ?>
            <div class="explorer__folders-root">
                <img class='fileIcon' src='./Icons/folder.svg'>
                <h3><a href="./show-trash.php">/trash</a></h3>
            </div>
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
            <p class="content__folder-title">/<a class='content__folder-title' href='index.php'><?= $basePath ?></a><?= isset($currentUserPath) ? getBreadCrumb($currentUserPath, $basePath) : "" ?></a><?php if (isset($_SESSION["showing-trash"]) && $_SESSION["showing-trash"]) "<a class='content__folder-title' href='/file-to-trash.php'> / trash</a>" ?></p>
        </div>
        <div class="content__list">
            <?php
            if (isset($_SESSION["showing-trash"]) && $_SESSION["showing-trash"]) {
                showTrash();
            } else  isset($currentUserPath) ? displayInfoParentFolder($currentUserPath) : displayInfoParentFolder($basePath)
            ?>
        </div>

        <?php

            visualize();

        ?>
    </section>
    <?php
    if (isset($_SESSION["fileInfo"])) {
        echo "
                        <section class='details'>
                            <div class='details__header'>
                                <img class='fileIcon' src='./Icons/" .  ($type ? $type : '') . ".svg'>
                                <p classname='details__name'>" . ($name ? $name : '') . "</p>
                                <button type='button' data-open='modal1' class='details__btn--edit'><img class='fileIcon-medium' src='../../assets/icons/edit.svg'></button>
                                <button class='details__btn--delete' onclick='location.href=\"./file-to-trash.php?file=$currentUserPath\"'><img class='fileIcon-medium' src='../../assets/icons/delete.svg' onclick='location.href=\"./file-to-trash.php?file=$currentUserPath\"'></button>
                            </div>
                            <div class='details__content'>
                                <div class='details__flex'>
                                    <p><strong>Type:</strong></p>
                                    <p>" . ($type ? $type : '') . "</p>
                                </div>
                                <div class='details__flex'>
                                    <p><strong>Size:</strong></p>
                                    <p>" . ($size ? $size : '') . "</p>
                                </div>
                                <div class='details__flex'>
                                    <p><strong>Modified:</strong></p>
                                    <p>" . ($modified ? $modified : '') . "</p>
                                </div>
                                <div class='details__flex'>
                                    <p><strong>Created:</strong></p>
                                    <p>" . ($created ? $created : '') . "</p>
                                </div>
                            </div>
                            <button class='details__preview' id='btn-show'>Preview</button>
                        </section>";
    }

    if (isset($_SESSION["fileInfoTrash"]) && isset($_SESSION["fileInfoTrash"])) {
        $currentUserPathTrash = $_SESSION["fileInfoTrash"]["path"];
        $nameTrash = $_SESSION["fileInfoTrash"]["name"];
        $typeTrash = strtoupper($_SESSION["fileInfoTrash"]["type"]);
        $sizeTrash = $_SESSION["fileInfoTrash"]["size"];
        $modifiedTrash = $_SESSION["fileInfoTrash"]["modified"];
        $createdTrash = $_SESSION["fileInfoTrash"]["created"];
        $shortNameTrash = $_SESSION["fileInfoTrash"]["shortName"];
        echo "
                        <section class='details'>
                            <div class='details__header'>
                                <img class='fileIcon' src='./Icons/" .  ($typeTrash ? $typeTrash : '') . ".svg'>
                                <p classname='details__name'>" . ($nameTrash ? $nameTrash : '') . "</p>
                                <button class='details__btn--delete' onclick='location.href=\"./delete_file.php?file=$currentUserPathTrash\"'><img class='fileIcon-medium' src='../../assets/icons/delete.svg' onclick='location.href=\"./delete_file.php?file=$currentUserPathTrash\"'></button>
                            </div>
                            <div class='details__content'>
                                <div class='details__flex'>
                                    <p><strong>Type:</strong></p>
                                    <p>" . ($typeTrash ? $typeTrash : '') . "</p>
                                </div>
                                <div class='details__flex'>
                                    <p><strong>Size:</strong></p>
                                    <p>" . ($sizeTrash ? $sizeTrash : '') . "</p>
                                </div>
                                <div class='details__flex'>
                                    <p><strong>Modified:</strong></p>
                                    <p>" . ($modifiedTrash ? $modifiedTrash : '') . "</p>
                                </div>
                                <div class='details__flex'>
                                    <p><strong>Created:</strong></p>
                                    <p>" . ($createdTrash ? $createdTrash : '') . "</p>
                                </div>
                            </div>
                            <button class='details__preview' id='btn-show'>Preview</button>
                        </section>";
    }
    unset($_SESSION["fileInfoTrash"]);
    unset($_SESSION["fileInfo"]);
    unset($_SESSION["showing-trash"]);
    ?>
    <!-- File Upload Modal  -->
    <article class="modal__file" id="modal__file">
        <div class="modal__content-file" id="modal__content-file">
            <button id="button-close-file" class="modal__close-file">X</button>
            <form id="modal-form-file" method="post" enctype="multipart/form-data" action=<?= "./upload.php?realPath=$currentUserPath" ?>>
                <div class="padding-1">
                    <label for="fileUpload">Title :</label>
                    <input type="file" id="fileUpload" name="fileUpload" value="" required />
                </div>
                <div class="padding-1">
                    <button id="cancel-modal-file" class="button--small">
                        Cancel
                    </button>
                    <button type="submit" name="submit" class="button--small">Submit</button>
                </div>
            </form>
        </div>
    </article>

    <div class="editing__modal" id="modal1" data-animation="slideInOutLeft">
        <div class="modal-dialog">
            <header class="modal-header">
                <h2 class="modal__title">RENAME FILE</h2>
                <button class="close-modal" aria-label="close modal" data-close>
                    ✕
                </button>
            </header>
            <section class="modal_content">
                <form action=<?= "./edit_file.php?file=./" . $currentUserPath ?> method="post" class="modal__form">
                    <input type="text" class="modal__input" name="newName" placeholder=<?= $shortName ?> />
                    <input class="modal__btn" type="submit" value="Edit">
                </form>
            </section>
            <footer class="modal-footer">
                Choose wisely a new name for your file
            </footer>
        </div>
    </div>
</main>
</body>


</html>