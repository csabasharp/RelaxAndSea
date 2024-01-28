<?php

class PDOOP {

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
            echo "<a class='error'>Csatlakozás sikertelen: ".$e->getMessage()."</a>";
        }
        
    }

    function __construct(){
        require "./conf/init.php";
        $this->servername = $servername;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->charset = $charset;

        print $this->servername." ".$this->user." ".$this->password." ".$this->dbname." ".$this->charset;
    }

    public function select($select = [], $params = [])
    {
        //hogyha select az egy tömb akkor szétszedi elemeire és össze rakja a lekérdezést
        //hogyha string akkor kéri a "params" tömböt is hogy elvégezze a lekérdezést
        if(!is_null($this->conn))
        {
            if(is_array($select)&&!is_string($select))
            {
                $params = [];
                $selected = "";
                if (isset($select['SELECT'])) {
                    $selected .= "SELECT ".$select['SELECT']." ";
                }
                else
                {
                    array_push($params,"*");
                }

                if (isset($select['FROM'])) {
                    $selected .= "FROM ".$select['FROM']." ";
                }
                else {
                    return false;
                }

                if (isset($select['WHERE'])) {
                    $selected .= "WHERE {$select['WHERE']} ";
                }
                if (isset($select['GROUP BY'])) {
                    $selected .= "GROUP BY {$select['GROUP BY']} ";
                }
                if (isset($select['HAVING'])) {
                    $selected .= "HAVING {$select['HAVING']} ";
                }
                if (isset($select['ORDER BY'])) {
                    $selected .= "ORDER BY {$select['ORDER BY']} ";
                }
                if (isset($select['LIMIT'])) {
                    $selected .= "LIMIT {$select['LIMIT']} ";
                }

                $select = $selected.";";
                
                try {
                    $stmt = $this->conn->prepare($select);
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo "<p class='error'>Lekérdezés hiba: ".$e->getMessage()."</p>";
                    return false;
                }
            }
            else {
                if (is_string($select) && str_starts_with($select,"SELECT")) {
                    try {
                        $stmt = $this->conn->prepare($select);
                        $stmt->execute($params);
                    } catch (PDOException $e) {
                        echo "<p class='error'>Lekérdezés hiba: ".$e->getMessage()."</p>";
                        return false;
                    }
                }
                else {
                    return false;
                }
                
            }
            return $stmt->fetchAll();
        }
        print "<p class='error'>Kapcsolat nem volt létrehozva futtatás előtt.</p>";
        return false;
    }

    /*public function select($select = [], array $params = [])
    {
        //hogyha select az egy tömb akkor szétszedi elemeire és össze rakja a lekérdezést
        //hogyha string akkor kéri a "params" tömböt is hogy elvégezze a lekérdezést
        if(!is_null($this->conn))
        {
            if(is_array($select)&&!is_string($select))
            {
                $params = [];
                $selected = "";
                if (isset($select['SELECT'])) {
                    $selected = "SELECT ";
                    $num = 0;
                    $split = preg_split("/,/",$select['SELECT'],-1);
                    foreach ($split as $part) {
                        ($num > 0) ? $selected .= ", " : "";
                        $params[":s".$num] = $part;
                        unset($params[$num]);
                        $selected .= ":s".$num;
                        $num++;
                    }
                    $selected .= ' ';
                }
                else
                {
                    $params[":s0"] = "*";
                    $selected .= ":s0";
                    # array_push($params,"*");
                }

                if (isset($select['FROM'])) {
                    $selected .= "FROM ".$select['FROM']." ";
                }
                else {
                    return false;
                }

                if (isset($select['WHERE'])) {
                    $selected .= "WHERE {$select['WHERE']} ";
                }
                if (isset($select['GROUP BY'])) {
                    $selected .= "GROUP BY {$select['GROUP BY']} ";
                }
                if (isset($select['HAVING'])) {
                    $selected .= "HAVING {$select['HAVING']} ";
                }
                if (isset($select['ORDER BY'])) {
                    $selected .= "ORDER BY {$select['ORDER BY']} ";
                }
                if (isset($select['LIMIT'])) {
                    $selected .= "LIMIT {$select['LIMIT']} ";
                }

                $select = $selected.";";
                

                print($select."".print_r($params));
                try {
                    $stmt = $this->conn->prepare($select);
                    $stmt->execute($params);
                    return $stmt->fetchAll();
                } catch (PDOException $e) {
                    echo "<p class='error'>Tömb alapú lekérdezés hiba: ".$e->getMessage()."</p>";
                    return false;
                }
            }
            else {
                if (is_string($select) && str_starts_with($select,"SELECT")) {
                    print_r($params);
                    try {
                        $stmt = $this->conn->prepare($select);
                        $stmt->execute($params);
                        return $stmt->fetchAll();
                    } catch (PDOException $e) {
                        echo "<p class='error'>Lekérdezés hiba: ".$e->getMessage()."</p>";
                        return false;
                    }
                }
                else {
                    return false;
                }
                
            }
        }
        print "<p class='error'>Kapcsolat nem volt létrehozva futtatás előtt.</p>";
        return false;
    }
    */

    public function insert(string $select, array $params)
    {
        if(!is_null($this->conn))
        {
            if (is_string($select)) {
                try {
                    $stmt = $this->conn->prepare($select);
                    $stmt->execute($params);
                    print "<p class='alert'>Sikeres feltöltés!</p>";
                    return true;
                } catch (PDOException $e) {
                    echo "<p class='error'>Feltöltés hiba: ".$e->getMessage()."</p>";
                    return false;
                }
            }
            
        }
        print "<p class='error'>Kapcsolat nem volt létrehozva futtatás előtt.</p>";
        return false;
    }
}


?>