<?php
session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
require_once("./user_actions.php");
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
            <form action="./new_folder.php" method="post" class="new-">
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
                    $fileExtension = explode(".", $v);
                    // if (is_array($v)) {
                    //     foreach (scandir($basePath . $v) as $f) {
                    //         echo "<div class='folder2__element'>$v</div>";
                    //     }
                    // };
                    if(is_dir($v)) {
                        echo "<img class='fileIcon' src='./Icons/folder.svg'><div class='folder1__element'>$v</div>";
                    } else {
                        echo "<img class='fileIcon' src='./Icons/$fileExtension[1].svg'><div class='folder1__element'>$v</div>";
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
                <p>PHP</p>
                <p>Size</p>
                <p>500Kb</p>
                <p>Modified</p>
                <p>12/01/2021</p>
                <p>Created</p>
                <p>1/01/2021</p>
            </div>
        </section>
    </main>
</body>

</html>