<?php

require_once("./user_actions.php");


$file = $_GET["file"];

fileToTrash($file);
