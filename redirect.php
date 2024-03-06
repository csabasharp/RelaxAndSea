<?php

header("Location: ".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'],true,301);
die();

?>