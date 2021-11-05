<?php
require_once("../config/app.php");
require_once("./session_control.php");
session_start();
isset($_SESSION["username"]) ? header("Location: ./root") : "";
$title = "Landing Page";
include(ROOT_PATH . "inc/_head.php");
?>
<div class="darkbg">
    <h1 class="login__title">A FILE MANAGER FOR THE ORGANIZED ONES</h1>
    <h2 class="login__subtitle">Create your own architecture of folders and keep track of all your stuff!</h2>

    <div class="login__div">
        <form method="post" action="./login.php" class="login">
            <input type="text" class="login__input" placeholder="username" name="username">
            <input type="password" class="login__input" placeholder="password" name="password">
            <button type="submit" class="login__btn">Log in!</button>
        </form>
    </div>
    <h2 class="login__notlogged">Not registered yet? <a class="signup" href="./signup-page.php">Sign up</a> and start!</h2>
    <?php
    if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
            case "registered":
                echo "<div class='login__error'>Seems that you had already registered. Log in!</div>";
                break;
            case "unregistered":
                echo "<div class='login__error'>Seems that you are not registered yet!</div>";
                break;
            case "password":
                echo "<div class='login__error'>Wrong password. Who are you? 🧐</div>";
                break;
            default:
                break;
        }
    }
    if (isset($_GET["info"]) && $_GET["info"] === "loggedout") {
        echo "<div class='login__info'>Logged out successfuly. Hope you come back soon!</div>";
    }
    ?>
    </body>
</div>

</html>