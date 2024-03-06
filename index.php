<?php
require "assets/php/siteBuilder/index.php";

$sb = new siteBuilder();

try {
    $sb->headStart();

    $sb->headStop();

    $sb->bodyStart($_GET['page']);
    

    ?>
    <div id="tartalom">
      <?php if($_GET['page']==""){$sb->requirePart("login.php");} ?>
      <?php if($_GET['page']=="szobak"){$sb->requirePart("login.php");} ?>
    </div>

    <?php

    $sb->bodyStop();
} catch (\Throwable $th) {
    print $th;
}


?>