<?php
require_once("../config/app.php");
require_once("./session_control.php");
session_start();
isset($_SESSION["username"]) ? header("Location: ./root") : "";
$title = "Sign Up Page";
include(ROOT_PATH . "inc/_head.php");
?>

<div class="darkbg">
    <h1 class="login__title">A FILE MANAGER FOR THE ORGANIZED ONES</h1>
    <h2 class="login__subtitle">Create your own architecture of folders and keep track of all your stuff!</h2>

    <div class="login__div">
        <form method="post" action="./signup.php" class="login">
            <input type="text" class="login__input" placeholder="username" name="username">
            <input type="text" class="login__input" placeholder="email" name="email">
            <input type="password" class="login__input" placeholder="password" name="password">
            <input type="password" class="login__input" placeholder="repeat password" name="rep-password">
            <button type="submit" class="login__btn">Sign up!</button>
        </form>
    </div>
    <?php
    if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
            case "unmatch":
                echo "<div class='login__error'>Both passwords must match</div>";
                break;
            case "email":
                echo "<div class='login__error'>Your email looks weird, try a valid one instead</div>";
                break;
            case "short":
                echo "<div class='login__error'>Username or password is less than six characters long. Please, don't be lazy</div>";
                break;
            default:
                break;
        }
    }
    ?>
    </body>
</div>


</html>