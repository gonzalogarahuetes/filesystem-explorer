<?php
require_once("../../config/app.php");

session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
require_once("./user_actions.php");

if (isset($_SESSION["fileInfo"])) {
    $name = $_SESSION["fileInfo"]["name"];
    $type = strtoupper($_SESSION["fileInfo"]["type"]);
    $size = $_SESSION["fileInfo"]["size"];
    $modified = $_SESSION["fileInfo"]["modified"];
    $created = $_SESSION["fileInfo"]["created"];
    $shortName = $_SESSION["fileInfo"]["shortName"];
}

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
            $basePath = $_SESSION["username"] . "_root";
            listFolderFiles($basePath);
            ?>
            <div class="explorer__folders-root">
                <img class='fileIcon' src='./Icons/folder.svg'>
                <h3><a href="./file-to-trash.php">/trash</a></h3>
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
            <p class="content__folder-title">Folders</p>
        </div>
        <div class="content__list">
            <?php
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
                                        <p>$sizeOfFile</p>
                                        <p>$timeModified</p>
                                    </div>";
                }
            }
            ?>
        </div>
    </section>
    <?php
    if (isset($_SESSION["fileInfo"])) {
        echo "
                        <section class='details'>
                            <div class='details__header'>
                                <img class='fileIcon' src='./Icons/" .  ($type ? $type : '') . ".svg'>
                                <p classname='details__name'>" . ($name ? $name : '') . "</p>
                                <button type='button' data-open='modal1' class='details__btn--edit'><img class='fileIcon-medium' src='../../assets/icons/edit.svg'></button>
                                <button class='details__btn--delete' onclick='location.href=\"./delete_file.php?file=$basePath/$name\"'><img class='fileIcon-medium' src='../../assets/icons/delete.svg' onclick='location.href=\"./delete_file.php?file=$basePath/$name\"'></button>
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
                        </section>";
    }

    ?>
    <div class="modal" id="modal1" data-animation="slideInOutLeft">
        <div class="modal-dialog">
            <header class="modal-header">
                <h2 class="modal__title">RENAME FILE</h2>
                <button class="close-modal" aria-label="close modal" data-close>
                    ✕
                </button>
            </header>
            <section class="modal-content">
                <form action=<?= "./edit_file.php?file=./" . $basePath . "/" . $name ?> method="post" class="modal__form">
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
<script>
    <?php
        require_once(ROOT_PATH . "assets/js/functions.js");
    ?>
</script>
</body>


</html>