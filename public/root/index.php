<?php
session_start();
!$_SESSION["username"] ? header("Location: ../login.php") : "";
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
        <button class="header__logout">
            <a href="../logout.php">Logout</a>
        </button>
    </header>
    <main>
        <section class="explorer">
            <button class="new-folder"></button>
            <div class="explorer__folders"></div>
        </section>
        <section class="content">
            <div class="content__guide">
                <p class="guide__p">Name</p>
                <p class="guide__p">Size</p>
                <p class="guide__p">Modified</p>
                <button class="guide_upload">Upload</button>
            </div>
            <div class="content__current-folder">
                <i></i>
                <p>Banana.na</p>
            </div>
            <div class="contet__list"></div>
        </section>
        <section class="details">
            <div class="details__title">
                <i></i>
                <p>Title</p>
                <button class="details__btn--edit"></button>
                <button class="details__btn--delete"></button>
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