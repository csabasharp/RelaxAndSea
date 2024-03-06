<?php

class PDOOPCore{

    //Készítette Kiss Máté Dominik

    private $servername;
    private $user;
    private $password;
    private $dbname;
    private $charset;

    protected $conn;

    public function connect($dbname=null,$user=null,$password=null)
    {
        try {
            $this->conn = new PDO('mysql:host='.$this->servername.';dbname='.$this->dbname.";charset=".$this->charset,$this->user,$this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Csatlakozás sikertelen;".$e);
        }
    }

    function __construct(){
        $initCalled = true;
        #require "./php/core/init.php";
        $this->servername = "localhost";
        $this->user = "root";
        $this->password = "";
        $this->dbname = "relaxnsea";
        $this->charset = "utf8";
        $this->dev = false;
        #print $this->servername." ".$this->user." ".$this->password." ".$this->dbname." ".$this->charset;
    }

    public function launch(string $sql, array $params = null)
    {
        try {
            $stmt = $this->conn->prepare($sql);
            if (is_null($params)) {
                $stmt->execute();
            }else {
                $stmt->execute($params);
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {

            throw new Exception("Lekérdezés hiba.;".$e);
        }
    }

    public function oszlopAdatGet(string $table)
    {
        try {
            $stmt = $this->conn->prepare("SELECT ORDINAL_POSITION as 'colPos', COLUMN_NAME as 'colName', IF(EXTRA='auto_increment','ai',null) as 'extra' FROM information_schema.columns WHERE table_name = '".$table."'");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Lekérdezés hiba.;".$e);
        }
    }

    public function oszlopSzam(string $table)
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as 'colSzam' FROM information_schema.columns WHERE table_name = '".$table."'");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Lekérdezés hiba.;".$e);
        }
    }

    
}

?>