<?php
function login()
{
    session_start();

    $username = $_POST["username"];
    $password = $_POST["password"];

    echo $username;

    //Llamar a la base de datos

    if (checkuser($username, $password)) {
        $_SESSION["username"] = $username;
        header("Location: ./root");
    } else {
        header("Location: ./landing-page.php");
    }
}

function checkuser($username, $password)
{

    $usernamedb = "exampleUser";
    $passworddb = "example123";

    if ($username === $usernamedb && $password === $passworddb) {
        return true;
    } else {
        return false;
    }
}

function logout()
{
    session_start();

    unset($_SESSION);

    destroyCookie();

    session_destroy();

    header("Location: ./landing-page.php");
}

function destroyCookie()
{
    if (ini_get("session.use_cookies")) {

        $params = session_get_cookie_params();

        setcookie(

            session_name(),

            '',

            time() - 42000,

            $params["path"],

            $params["domain"],

            $params["secure"],

            $params["httponly"]

        );
    }
}
