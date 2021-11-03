<?php
session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
require_once("./user_actions.php");

if (isset($_SESSION["fileInfo"])) {
    $type = $_SESSION["fileInfo"]["type"];
    $size = $_SESSION["fileInfo"]["size"];
    $modified = $_SESSION["fileInfo"]["modified"];
    $created = $_SESSION["fileInfo"]["created"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>

<body>
    <header>
        <img src="" alt="Logo">
        <input type="text" class="header__search">
        <p class="header_welcome">Welcome, <?= $_SESSION["username"] ?></p>
        <button class="header__logout">
            <a href="../logout.php">Logout</a>
        </button>
    </header>
    <main>
        <section class="explorer">
            <form action="./user_actions.php" method="post" class="new-">
                <input type="text" name="newFolder" class="explorer__new">
                <button type="submit" class="new-folder"> New Folder</button>
            </form>
            <div class="explorer__folders"></div>
        </section>
        <section class="content">
            <div class="content__guide">
                <p class="guide__p">Name</p>
                <p class="guide__p">Size</p>
                <p class="guide__p">Modified</p>
                <button class="guide_upload">Upload</button>
            </div>
            <div class="content__folder">
                <?php
                $basePath = "./Files";
                $dirContent = scandir($basePath);
                foreach ($dirContent as $v) {
                    $currentFile = "$basePath/$v";
                    if (is_dir($currentFile)) {
                        echo "<div class='folder1__element'><a href='./select_file.php?file=$v'>$v</a></div>";
                    } else {
                        echo "<div class='folder1__element'><a href='./select_file.php?file=$v'>$v</a><p>" . filesize($currentFile) . "</p><p>" . filectime($currentFile) . "</p></div>";
                    }
                }
                ?>
            </div>
            <div class="contet__list"></div>
        </section>
        <section class="details">
            <div class="details__title">
                <i></i>
                <p>Title</p>
                <button class="details__btn--edit">Edit</button>
                <button class="details__btn--delete">Delete</button>
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