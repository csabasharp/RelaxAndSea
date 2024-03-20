<?php
    if (!defined("BUILT_BY_PHP")) {
      header("Location: ".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'],true,301);
      die();
  }
    else {
?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Relax & Sea</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" type="text/css" href=<?php echo $this->toPlace("assets/styling/style.css")?> />
    <link rel="stylesheet" type="text/css" href=<?php echo $this->toPlace("assets/styling/overwriteBootstrap.css")?> />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"
    />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $this->toPlace("/assets/javascript/jsAjax.js")?>" defer></script>
    <script src="<?php echo $this->toPlace("/assets/javascript/jsTools.js")?>" defer></script>
<?php
    }

?>