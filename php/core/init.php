<?php

if (!defined("INIT_CALLED")) {
    exit("Initialisation called outside of function.");
}

$servername = "localhost";
$user = "root";
$password = "";
$dbname = "relaxnsea";
$charset = "utf8";
if (isset($_SESSION["developer"]) && $_SESSION["developer"] == true) {
    $dev = true;
}


?>