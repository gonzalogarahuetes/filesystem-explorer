<?php
require_once("./session_control.php");
session_start();
isset($_SESSION["username"]) ? header("Location: ./root") : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
</head>

<body>
    <div class="login_div">
        <form method="post" action="./login.php" class="login">
            <input type="text" class="login__username" placeholder="username" name="username">
            <input type="password" class="login_pass" placeholder="password" name="password">
            <button type="submit" class="login_btn">Log in!</button>
        </form>
    </div>
</body>

</html>