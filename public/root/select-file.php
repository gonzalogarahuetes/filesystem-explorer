<?php

    function selectFile(){
        $file = $_GET["file"];
        echo "$file";

        // header("Location: ./index.php");
    }
    selectFile();