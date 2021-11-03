<?php
require_once("../config/app.php");
require_once("./session_control.php");
session_start();
isset($_SESSION["username"]) ? header("Location: ./root") : "";
$title = "Landing Page";
include(ROOT_PATH . "inc/_head.php");
?>

    <div class="login_div">
        <form method="post" action="./login.php" class="login">
            <input type="text" class="login__username" placeholder="username" name="username">
            <input type="password" class="login_pass" placeholder="password" name="password">
            <button type="submit" class="login_btn">Log in!</button>
        </form>
    </div>
    </body>

</html>