<?php
if (!defined("VALID_API_KEY")) {
    $simpleRest->setHttpHeaders("application/json",401);
    die(json_encode(array(
        "code" => 401,
        "error" => "No valid API key present"
    )));
}

class Crud extends PDOOPCore
{
    private $data;

    private $table;

    private $id;

    function __construct($data, $table, $id = null)
    {
        parent::__construct();
        $this->data = $data;
        $this->table = $table;
        $this->id = $id;
        $this->connect();
    }

    public function Create()
    {
        $reply = ["action" => "created"];
        
        return $reply;
    }

    public function Read()
    {
        $this->connect();
        $reply = ["action" => "read"];
        $query = "SELECT * FROM $this->table";
        $params = array();
        if (!is_null($this->id)) {
            array_push($params,$this->id);
            $query .= " WHERE id LIKE ?";
        }
        $query .= ";";
        
        try {
            $reply["$this->table"] = $this->launch($query,$params);
        } catch (\Throwable $th) {
            $reply = array();
        }
        
        if (empty($reply["$this->table"])) {
            $reply = array();
        }

        return $reply;
    }

    public function Update()
    {
        $reply = ["action" => "updated"];
        return $reply;
    }

    public function Delete()
    {
        $reply = ["action" => "created"];
        return $reply;
    }
}


?>