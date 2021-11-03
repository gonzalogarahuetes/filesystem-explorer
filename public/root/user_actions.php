<?php

function newFolder()
{
    $folderName = $_POST["newFolder"];
    $path = "C:/XAMPP/htdocs/PhpBasicWorkshop/manage-files/filesystem-explorer/public/root/Files/$folderName";


    mkdir($path, 0777, true);

    $content = scandir("C:/XAMPP/htdocs/PhpBasicWorkshop/manage-files/filesystem-explorer/public/root/Files/$folderName");

    print_r($content);

    //Crear los divs que sean

    header("Location: ./index.php");
}
