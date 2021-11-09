<?php
require_once("./user_actions.php");

session_start();

$file = $_GET["file"];

selectFile($file);
