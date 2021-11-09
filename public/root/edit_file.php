<?php

session_start();
require_once("./user_actions.php");

if (isset($_POST["newName"])) {
    $newName = $_POST["newName"];
}

$file = $_GET["file"];


editFile($file, $newName);
