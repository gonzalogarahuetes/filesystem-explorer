<?php

function newFolder()
{
    $folderName = $_POST["newFolder"];
    $path = "./Files/$folderName";

    mkdir($path, 0777, true);

    header("Location: ./index.php");
}
