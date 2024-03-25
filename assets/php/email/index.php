<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./PHPMailer/src/PHPMailer.php";
require "./PHPMailer/src/Exception.php";
require "./PHPMailer/src/SMTP.php";

$email = new PHPMailer(TRUE);



try {
    $email->setLanguage("hu");
    $email->CharSet = "UTF-8";
    $email->setFrom('relax.and.sea.hotel@outlook.hu');

    $email->addAddress($_GET["email"]);
    $email->Subject = 'Teszt E-mail';
    $email->Body = 'Üdv! Mivel google elutasította az előző levlet ezért át lett írva ez a szöveg, de ne aggódj a teszt sikeres volt!';

    $email->isSMTP();
    $email->Host = 'smtp-mail.outlook.com';
    $email->Port = 587;
    $email->SMTPAuth = true;
    $email->SMTPSecure = 'tls';
    $email->Username = 'relax.and.sea.hotel@outlook.hu';
    $email->Password = ""; //jelszo megadása

    if(!$email->send());
    {
        die("There was an issue with sending the email.");
    }
} catch (\Exception $e) {
   die(var_dump($e));
}

?>