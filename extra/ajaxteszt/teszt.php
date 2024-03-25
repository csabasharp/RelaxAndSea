<?php

$text = file_get_contents('php://input');
if (strlen($text) == 0) {
    $error = "empty";
    $text = json_encode($error);
}
print $text;
?>