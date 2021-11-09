<?php
/* Help to set up and allow PHP to generate the link to other pages file automatically */
$arrayPath = explode("config", __DIR__);
define("BASE_URL", $arrayPath[0]);
define("ROOT_PATH", $arrayPath[0]);
