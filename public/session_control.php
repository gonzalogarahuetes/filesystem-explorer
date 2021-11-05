<?php
function login()
{
    session_start();

    $username = $_POST["username"];
    $password = $_POST["password"];

    //Llamar a la base de datos

    $checkedUser = checkuser($username, $password);

    if ($checkedUser === true) {
        $_SESSION["username"] = $username;
        header("Location: ./root");
    } elseif (!$checkedUser) {
        header("Location: ./landing-page.php?error=unregistered");
    } elseif ($checkedUser === "password") {
        header("Location: ./landing-page.php?error=password");
    }
}

function checkuser($username, $password)
{
    $explodePath = explode("public", __DIR__);
    $usersJsonFile = $explodePath[0] . "assets\data\users.json";
    $jsonData = file_get_contents($usersJsonFile);
    $usersData = json_decode($jsonData, true);

    foreach ($usersData as $user) {
        if (array_search($username, $user) !== false) {
            $currentUser = $user;
        }
    }

    if (isset($currentUser) && $currentUser["password"] === $password) {
        return true;
    } elseif (isset($currentUser) && $currentUser["password"] !== $password) {
        return "password";
    } elseif (!isset($currentUser)) {
        return false;
    }
}

function logout()
{
    session_start();

    unset($_SESSION);

    destroyCookie();

    session_destroy();

    header("Location: ./landing-page.php?info=loggedout");
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
    session_start();
    if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["rep-password"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $reppass = $_POST["rep-password"];
    } else {
        header("Location: ./signup-page.php?error=incomplete");
    }

    $explodePath = explode("public", __DIR__);
    $usersJsonFile = $explodePath[0] . "assets\data\users.json";


    $checkUser = checkSignUp($username, $password, $reppass, $email, $usersJsonFile);

    if ($checkUser === "registered") {
        header("Location: ./landing-page.php?error=registered");
        return;
    } elseif ($checkUser === "unmatch") {
        header("Location: ./signup-page.php?error=unmatch");
        return;
    } elseif ($checkUser === "short") {
        header("Location: ./signup-page.php?error=short");
        return;
    } elseif ($checkUser === "email") {
        header("Location: ./signup-page.php?error=email");
        return;
    }

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

    $_SESSION["username"] = $username;
    header("Location: ./root");
}

function checkSignUp($username, $password, $reppass, $email, $usersJsonFile)
{
    $jsonData = file_get_contents($usersJsonFile);

    $usersData = json_decode($jsonData, true);

    //case user already registered

    foreach ($usersData as $user) {
        if (array_search($username, $user) !== false) {
            $error = "registered";
        }
    }

    //email not valid

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !isset($error)) $error = "email";

    //case short password or username

    if (strlen($password) < 6 || strlen($username) < 6) $error = "short";

    //case passwords unmatch

    if ($password !== $reppass && !isset($error)) $error = "unmatch";

    if (!isset($error)) $error = "";

    return $error;
}
