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
        array_push($params,constant("VALID_API_KEY"),$message);
        $this->launch($query,$params);
    }

    public function Create()
    {
        $reply = ["action" => "created"];
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
                } catch (\Throwable $th) {
                    $reply = array();
                }
            }
            
        }
        else {
            $reply = array();
        }

        if (empty($reply)) {
            $this->SaveLog("Attempted create in $this->table, but failed.");
        }
        else {
            $this->SaveLog("Created new entry in $this->table with id of ".$this->rekordSzam($this->table));
        }
        return $reply;
    }

    public function Read()
    {
        $reply = ["action" => "read"];
        $query = "SELECT * FROM $this->table";
        $params = array();
        $message = "Read the contents of $this->table";

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
        }
        
        if (isset($reply["$this->table"])&&empty($reply["$this->table"])) {
            $message .= "failed";
            $reply = array();
        }
        elseif (!empty($reply["$this->table"])) {
            $message .= "success";
        }

        try {
            $this->SaveLog($message);
        } catch (\Throwable $th) {
            print $th;
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