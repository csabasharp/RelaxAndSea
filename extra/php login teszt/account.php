<?php
# Ez a script fogja elvégezni a megfelelő műveleteket ahhoz hogy belépjen vagy regisztráljon valaki
if (isset($_POST["submit"])) {
    switch ($_POST["submit"]) {
        case 'regist':
           define("TOKEN","regist");
            break;

        case 'login':
            define("TOKEN","login");
            break;
            
        case 'settings':
            define("TOKEN","settings");
            break;

        default:
            
            break;
    }
    
}


?>