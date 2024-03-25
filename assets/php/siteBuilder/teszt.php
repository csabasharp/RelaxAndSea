<?php
require "index.php";

$sb = new siteBuilder();

try {
    $sb->headStart();

    $sb->headStop();

    $sb->bodyStart();

    $sb->bodyStop();
} catch (\Throwable $th) {
    print $th;
}


?>