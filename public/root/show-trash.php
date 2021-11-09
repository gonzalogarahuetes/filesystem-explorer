<?php

require_once("./user_actions.php");

session_start();
$_SESSION["showing-trash"] = true;

header("Location: ./index.php");
