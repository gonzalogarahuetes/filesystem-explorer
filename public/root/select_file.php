<?php

require_once("./user_actions.php");

$file = $_GET["file"];

echo file_exists($file);

selectFile($file);
