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

    private function SaveLog(string $message)
    {
        $query = "INSERT INTO logs(userId, uzenet) VALUES(?,?)";
        $params = array();
        if (constant("VALID_API_KEY")) {
            array_push($params,constant("VALID_API_KEY"),$message);
            $this->launch($query,$params);
        }
    }

    private function RecordExists(int $id = null, string $table = null) : bool
    {
        if (is_null($id) || !is_int($id)) {
            $id = $this->id;
        }
        if (is_null($table) || !is_string($table)) {
            $table = $this->table;
        }
        try {
            $query = "SELECT * FROM ".$table." WHERE id = ?;";
            $params[0] = $id;
            $result = $this->launch($query,$params);
        } catch (\Throwable $th) {
            throw $th;
        }

        if (count($result) == 0 || count($result) > 1) {
            return false;
        }
        else {
            return true;
        }
    }

    public function Create()
    {
        $reply = ["action" => "create"];
        if (isset($this->data->values)&&is_array($this->data->values)) {
            $oszlopok = $this->oszlopAdatGet($this->table);

            $query = "INSERT INTO $this->table(";
            $oszlopszam = 0;

            foreach ($oszlopok as $oszlop) {
                if (is_null($oszlop["extra"])) {
                    $oszlopszam++;
                }
            }

            $szamlalo = 0;
            foreach($oszlopok as $oszlop) {
                if (is_null($oszlop["extra"])) {
                    ($szamlalo > 0 && $szamlalo < $oszlopszam) ? $query .= ", " : "";
                    $query .= $oszlop["colName"];
                    $szamlalo++;
                }
            }
            $query .= ") VALUES(";

            if ($oszlopszam!=count($this->data->values)) {
                $reply = array();
            }
            else
            {
                for ($i=0; $i < $oszlopszam; $i++) { 
                    ($i > 0 && $i < $oszlopszam) ? $query .= ", " : "";
                        $query .= "?";
                }
                $query .= ");";
                try {
                  $this->launch($query,$this->data->values);
                  $reply["id"] =  $this->conn->lastInsertId();
                } catch (\Throwable $th) {
                    $reply = array();
                }
            }
            
        }
        else {
            $reply = array();
        }

        if (empty($reply)) {
            $message = "attempted create in $this->table, but failed.";
            $this->SaveLog($message);
            $reply["status"] = "failed";
            $reply["message"] = $message;
        }
        else {
            $message = "created new entry in $this->table with id of ".$reply["id"];
            $this->SaveLog($message);
            $reply["status"] = "success";
            $reply["message"] = $message;
        }
        return $reply;
    }

    public function Read()
    {
        $reply = ["action" => "read"];
        $query = "SELECT * FROM $this->table";
        $params = array();
        $message = "read the contents of $this->table";

        if (!is_null($this->id)) {
            array_push($params,$this->id);
            $message .= " with id of $this->id.";
            $query .= " WHERE id LIKE ?";
        }
        $message .= " Status: ";
        $query .= ";";
        try {
            $reply["$this->table"] = $this->launch($query,$params);
            
        } catch (\Throwable $th) {
            $message .= "failed";
            $reply = array();
            $reply["status"] = "failed";
        }
        
        if (isset($reply["$this->table"])&&empty($reply["$this->table"])) {
            $message .= "failed";
            $reply = array();
            $reply["status"] = "failed";
        }
        elseif (!empty($reply["$this->table"])) {
            if (!is_null($reply["$this->table"]) && isset($this->id)) {
                $reply[$this->table]= $reply[$this->table][0];
            }
            $message .= "success";
            $reply["status"] = "success";
        }

        try {
            $this->SaveLog($message);
        } catch (\Throwable $th) {
            print $th;
        }
        $reply["action"] = "read";

        return $reply;
    }

    public function Update()
    {
        $failcheck = false;
        $reply = ["action" => "update"];
        if (!is_int(constant("VALID_API_KEY"))) {
            $reply["status"] = "failed";
            $failcheck = true;
        }
        else if(is_null($this->id))
        {
            $reply["status"] = "failed";
            $reply["reason"] = "record was not selected for update";
            $failcheck = true;
        }
        else if(!isset($this->data->values))
        {
            $reply["status"] = "failed";
            $reply["reason"] = "values were not sent for update";
            $failcheck = true;
        }
        else if (!$this->RecordExists()) {
            $reply["status"] = "failed";
            $reply["reason"] = "record does not exist";
            $failcheck = true;
        }

        if ($failcheck === true) {
            return $reply;
        }

        $query = "UPDATE $this->table SET ";
        
        try {
            $values = get_object_vars($this->data->values);
            $keys = array_keys($values);
        } catch (\Throwable $th) {
            $reply["status"] = "failed";
            $reply["reason"] = "invalid syntax for values";
            return $reply;
        }

        $params = [];
        $hossz = count($keys);
        for ($i=0; $i < $hossz; $i++) { 
            $params[$i] = $values[$keys[$i]];
            $query .= $keys[$i]." = ?";
            if ($i+1 < $hossz) {
                $query .= ", ";
            }
        }
        $query .= " WHERE id = ".$this->id.";";
        
        try {
            $this->launch($query,$params);
            $reply["status"] = "success";
            $reply["result"] = "updated record ".$this->id." successfully";
        } catch (\Throwable $th) {
            $reply["status"] = "failed";
            $reply["result"] = "updating was unsuccessful";
        }

        return $reply;
        
    }

    public function Delete()
    {
        $reply = ["action" => "delete"];

        if(is_null($this->id))
        {
            $query = "TRUNCATE TABLE ".$this->table.";";

            try {
                $this->launch($query);
                $reply["status"] = "success";
                $reply["result"] = "deleted all records in ".$this->table;
            } catch (\Throwable $th) {
                $reply["status"] = "failed";
                $reply["result"] = "deleting was unsuccessful. ".$th;
            }
        }
        else
        {
            if (!$this->RecordExists()) {
                $reply["status"] = "success";
                $reply["reason"] = "record already did not exist";
            }
            else {
                $query = "DELETE FROM ".$this->table." WHERE id = ?;";
                $params[0] = $this->id;

                try {
                    $this->launch($query,$params);
                    $reply["status"] = "success";
                    $reply["result"] = "deleted record ".$this->id." successfully";
                } catch (\Throwable $th) {
                    $reply["status"] = "failed";
                    $reply["result"] = "deleting was unsuccessful";
                }
            }
        }
    
        return $reply;
    }
}

?>