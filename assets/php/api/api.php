<?php
require("simpleRest.php");

function apiCheck(string $key)
{
    # le ellenőrzi hogy melyik dolgozó apikulcsa volt használva, 
    # lementi a dolgozó azonosítóját, és csinál egy új log-rekordot
    # a dolgozó cselekménye alapján
    try {
        require "./../database/pdoop.php";
        $pdoop = new PDOOPCore();
        $pdoop->connect();
    } catch (\Throwable $th) {
        die(json_encode(array("error"=>$th)));
    }
    

    $select = "SELECT userId FROM `dolgozok` WHERE apiKulcs LIKE ?";
    $params = array();
    $params[0] = $key;
    try {
        $result = $pdoop->launch($select,$params);
    } catch (\Throwable $th) {
        die(json_encode(array($th->getMessage())));
    }
    
    $key = $result[0]["userId"];

    if (!empty($result)) {
        define("VALID_API_KEY",$key);
    }
    
}

$data = file_get_contents('php://input');
$data = json_decode($data);
$reply = array();
$reply["code"] = 200;
$reply["table"] = $_GET["table"];
if (!is_null($_GET["id"])) {
    $reply["record"] = $_GET["id"];
}

# define("VALID_API_KEY",$key); //tesztelés céljából mindig deklarálva

if (isset($data->api_key) && !empty($data->api_key)) {
    # API kulcs ellenőrzés
    apiCheck($data->api_key);
    # ha sikeresen talált megeggyező kulcsot akkor ->
    require "crud.php";
    $reply["key_used"] = $data->api_key;
    $reply["user_id"] = constant("VALID_API_KEY");
}
elseif(isset($_SESSION["api_key"])&&!empty($_SESSION["api_key"]))
{
    # hogyha van session a felhasználóval és request body-ban nincs apikulcs
    # akkor megnézi hogy a sessionban ha van kulcs akkor az pontos-e
    apiCheck($_SESSION["api_key"]);
    require "crud.php";
    $reply["key_used"] = $_SESSION["api_key"];
    $reply["user_id"] = constant("VALID_API_KEY");
}
else {
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        $simpleRest->setHttpHeaders("application/json",401);
        $reply = array(
            "code" => 401,
            "error" => "No API key present",
            "table" => $_GET["table"],
        );
        if (!is_null($_GET["id"])) {
            $reply["record"] = $_GET["id"];
        }
        die(json_encode($reply));
    }
    else
    {
        try {
            require "./../database/pdoop.php";
            $pdoop = new PDOOPCore();
            $pdoop->connect();
        } catch (\Throwable $th) {
            die(json_encode(array("error"=>$th)));
        }

        $reply["key_used"] = null;
        $reply["user_id"] = null;
        
        if ($_SERVER["REQUEST_METHOD"] == "PUT") {
            $select = "SELECT id FROM `userek` WHERE nev LIKE ? AND jelszo LIKE ?";
            $params = array();
            try {
                $params[0] = $data->nev;
                $params[1] = $data->jelszo; #md5($data->jelszo);
                $result = $pdoop->launch($select,$params);
            } catch (\Throwable $th) {
                die(json_encode(array($th->getMessage())));
            }
            
            $key = $result[0]["id"];
    
            if (!empty($result)) {
                define("VALID_API_KEY",$key);
                $reply["user_id"] = $key;
            }
        }
        else {
            define("VALID_API_KEY",false);
        }
        
        require "crud.php";
        
    }
}

$code = 200;

if (!isset($data)||is_null($data)) {
    $data = array();
}
$crud = new Crud($data,$_GET["table"],$_GET["id"]);
# $crud->connect();
switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST": //create
        $result = $crud->Create();
        $reply["status"] = $result["status"];
        $reply["action"] = $result["action"];
        unset($result["status"]);
        unset($result["action"]);
        if ($reply["status"] == "success") {
            $reply["result"] = $result;
            $code = 201;
        }
        else if($reply["status"] == "failed") {
            $code = 400;
            $reply["result"] = $result;
            $reply["reason"] = $simpleRest->getHttpStatusMessage($code);
        }
        else {
            $code = 500;
            $reply["reason"] = $simpleRest->getHttpStatusMessage($code);
        }
        break;
        
    case 'GET': //read
        $result = $crud->Read();
        $reply["action"] = $result["action"];
        $reply["status"] = $result["status"];
        unset($result["status"]);
        unset($result["action"]);
        if (!empty($result)) {
            $reply["result"] = $result;
        }
        else {
            $code = 404;
            $reply["reason"] = $simpleRest->getHttpStatusMessage($code);
        }
        break;
    
    case "PUT": //update
        $result = $crud->Update();
        $reply["action"] = $result["action"];
        $reply["status"] = $result["status"];
        
        if ($reply["status"] == "success") {
            if (!is_null($result["result"])) {
                $reply["result"] = $result["result"];
            }
            $code = 200;
        }
        else if($reply["status"] == "failed") {
            $code = 400;
            if (!is_null($result["result"])) {
                $reply["result"] = $result["result"];
            }
            $reply["reason"] = $simpleRest->getHttpStatusMessage($code);
            if (isset($result["reason"]) && !is_null($result["reason"])) {
                $reply["reason"] = $result["reason"];
            }
            
        }
        else {
            $code = 500;
            $reply["reason"] = $simpleRest->getHttpStatusMessage($code);
        }

        break;

    case "DELETE": //delete
        $reply["result"] = $crud->Delete();
        break;
    
    default:
        $code = 405;
        break;
}


$simpleRest->setHttpHeaders("application/json",$code);
$reply["code"] = $code;

$reply = json_encode($reply);
echo $reply;


?>