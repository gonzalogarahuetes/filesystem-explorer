<?php
require_once("./user_actions.php");
session_start();

if (isset($_SESSION["fileInfo"])) {
    unset($_SESSION["fileInfo"]);
}

$input = $_POST["search"];
$basePath = $_SESSION["username"] . "_root";
$items = searchItem($basePath, $input);
foreach ($items as $item) {
    echo $item;
    echo "<br>";
}




