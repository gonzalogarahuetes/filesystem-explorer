<?php

function visualize()
{
    $basePath = $_SESSION["username"] . "_root";

    if (isset($_SESSION["fileInfo"])) {
        $name = $basePath ."/". $_SESSION["fileInfo"]["name"];
        $type = $_SESSION["fileInfo"]["type"];
    }
    

    echo "
        <div id='modal' class='modal'>
            <span class='close' id='btn-hidde'>&times;</span>
    ";

    if(isset($type)){

        switch ($type) {
            case "mp4":
            case "mp3":
                echo "
                    <video controls class='modal-content'>
                        <source src='$name' type='video/mp4'>
                    </video>
                ";
                break;

            case "jpg":
            case "png":
            case "svg":
                echo "
                    <img src='$name' alt='' class='modal-content'>
                ";
                break;

            case "ogg":
                echo "
                    <audio controls>
                        <source src='$name' type='audio/ogg' class='modal-content'>
                    </audio>
                ";
                break;

            case "zip":
                $zip = zip_open("$name");
                if ($zip) {
                    while ($zip_entry = zip_read($zip)) {
                        echo "File content:\n";
                        $buf = zip_entry_read($zip_entry);
                        echo "$buf\n <br>";
                        zip_entry_close($zip_entry);
                    }
                    zip_close($zip);
                }
                break;

            case "pdf":
                echo "
                    <object data='$name' type='application/pdf'  class='modal-content'>
                        <embed src='$name' type='application/pdf' />
                </object>
                ";
                break;

            case "csv":
                define('CSV', '$name');
                $readCsv = array_map('str_getcsv', file(CSV));
                echo "<table border='1'>";
                foreach ($readCsv as $itemCsv) {
                    echo '<tr>';
                    foreach ($itemCsv as $elementoItemCSV) {
                        echo '<td>';
                        echo $elementoItemCSV;
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo "</table> ";
                break;

            case "doc":
            case "docx":
            case "odt":
            case "ppt":
            case "pptx":
                echo "
                    <p>The office file cannot be previsualized <a href='$name'>Download</a></p>
                ";
                break;

            case "exe":
                echo "
                    <p>This file cannot be previsualized <a href='$name'>Download</a></p>
                ";
                break;

            case "php":
                ini_set('highlight.comment', '#CCCCCC; font-weight: bold;');
                highlight_file($name);
                break;

            case "rar":
                echo "
                                    <p>This file cannot be previsualized <a href='$name'>Download</a></p>
                                ";
                break;

            case "txt":
                $archivo = file_get_contents("$name");
                $archivo = ucfirst($archivo);
                $archivo = nl2br($archivo);
                echo "
                    <strong class='modal-content'>Archivo de texto archivo.txt:</strong>
                    <br/><br/>
                    <p>$archivo</p>
                ";
                break;

            default:
                echo "
                    <p>You can't show this file <a href='$name'> Download</a></p>
                ";
        }
    }

    echo "
        </div>
    ";
}