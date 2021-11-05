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

function signup()
{
    if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["rep-password"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $reppass = $_POST["rep-password"];
    } else {
        header("Location: ./signup-page.php?error=incomplete");
    }

    //TODO check if both passwords match

    checkSignUp($username, $password, $reppass);

    $explodePath = explode("public", __DIR__);
    $usersJsonFile = $explodePath[0] . "assets\data\users.json";

    if (file_exists($usersJsonFile)) {
        $jsonData = file_get_contents($usersJsonFile);

        $usersData = json_decode($jsonData, true);
    } else {
        $usersData = [];
    }

    $usersData[] = [
        "username" => $username,
        "email" => $email,
        "password" => $password,
    ];

    $jsonData = json_encode($usersData, JSON_PRETTY_PRINT);

    file_put_contents($usersJsonFile, $jsonData); //this line equals:
    //fopen($usersJsonFile, "w");
    //fwrite($usersJsonFile, $jsonData);
    //fclose($usersJsonFile);


    // header("Location: ./");
}

function checkSignUp($username, $password, $reppass)
{
}
