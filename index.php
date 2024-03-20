<?php
require "assets/php/siteBuilder/index.php";
require "assets/php/curl/requestMenedzser.php";

#session_start();
#$_SESSION["username"] = "teszt";
#$_SESSION["uid"] = 1;
$sb = new siteBuilder();
$rm = new RequestMenedzser();

try {
    $sb->headStart();

    $sb->headStop();

    $sb->bodyStart($_GET['page']);
    

    ?>
    <div id="tartalom">
      <?php if($_GET['page']==""){$sb->requirePart("login.php");} ?>
      <?php if($_GET['page']=="szobak"){print $rm->apiRead("szobak");} ?>
    </div>

    <?php

    $sb->bodyStop();
} catch (\Throwable $th) {
    print $th;
}

$rm->close();
session_destroy();
?>