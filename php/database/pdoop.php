<?php

class PDOOPCore{

    //Készítette Kiss Máté Dominik

    protected $servername;
    protected $user;
    protected $password;
    protected $dbname;
    protected $charset;

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
        require "./../core/init.php";
        $this->servername = $servername;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->charset = $charset;
        $this->dev = $dev;
        # print $this->servername." ".$this->user." ".$this->password." ".$this->dbname." ".$this->charset;
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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

class PDOOP extends PDOOPCore
{
    // A PDOOP kifejezetten arra jó hogy felhasználóval kommunkálni tudjon az sql szerver,
    //attól függően hogy sikeres vagy sikertelen volt a művele

    public function simplePrepStat(string $sql, array $params = null)
    {
        try {
            return $this->launch($sql,$params);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    

    public function selectComplex($fields, array $params = null)
    {
        throw new Exception("Not Implemented", 1);
        
        if (is_null($this->conn)) {
            print $this->exceptionHandler("Kapcsolat nem volt létrehozva futtatás előtt");
        }

        if (is_array($fields)) { //ha tömb akkor tömbként kezelje
            if (is_null($params)) {
                # code...
            }
        }
        elseif (is_string($fields)&& $fields != "") { //ha string és nem üres akkor stringként kezelje
            if (is_null($params)) {
                # code...
            }
        }

        return false;
    }

    public function selectBeta($select = [], $params = [])
    {
        //hogyha select az egy tömb akkor szétszedi elemeire és össze rakja a lekérdezést
        //hogyha string akkor kéri a "params" tömböt is hogy elvégezze a lekérdezést
        throw new Exception("Not Implemented", 1);
        
        if(!is_null($this->conn))
        {
            throw new Exception($this->exceptionHandler("Kapcsolat nem volt létrehozva futtatás előtt."));
        }
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
                    $selected .= $part;
                    $num++;
                }
                $selected .= ' ';
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
                //ha van AND vagy OR akkor azon belül megkeresi hogy hol van beillesztendő adat
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
                throw new Exception($this->exceptionHandler("Lekérdezés hiba",$e));
            }
        }
        else {
            if (is_string($select) && str_starts_with($select,"SELECT")) {
                try {
                    $stmt = $this->conn->prepare($select);
                    $stmt->execute($params);
                } catch (PDOException $e) {
                    throw new Exception($this->exceptionHandler("Lekérdezés hiba",$e));
                }
            }
            else {
                return false;
            }
            
        }
        # return $stmt->fetchAll();
    }

    /* 
    Selecthez bekért adat: string, array
    A stringben benne van hogy tabla(oszlop1,oszlop2,...oszlopN)
    hogyha csak a tabla nevét kapja meg akkor lekérdezi az összes elemek számát abban atáblában
    Az arrayben benne van hogy melyik elemek kerülnek bele: array("kicsi",42,..."2003-21-3")
    */
    public function insert(string $insert, array $params)
    {
        /*
            Bekért "insert" string => tábla(mező1, mező2, ... mezőn)
            Bekért "params" tömb => ([0] => érték1, [1] => érték2, ... [n] => értékn)
        */
        if(is_null($this->conn))
        {
            throw new Exception("Kapcsolat nem volt létrehozva futtatás előtt.");
        }
        if (is_string($insert)) {

            $txt = preg_replace("~\(|\)~",", ",$insert);
            $table = preg_split("~, ~",$txt,-1);
            if (sizeof($table) > 1) { //hogyha csak a táblázat nevét kapja meg akkor nem törli az utolsó karaktert
                array_pop($table);
            }
            foreach ($this->oszlopAdatGet($table[0]) as $key => $value) {
                # code...
            }
            $stmt = "INSERT INTO ".$insert;


            try {
                $stmt = $this->conn->prepare($insert);
                $stmt->execute($params);
                return true;
            } catch (PDOException $e) {
                throw new Exception("Feltöltés hiba.".$e);
            }
        }
        throw new Exception("Ismeretlen hiba. Feltöltés sikertelen.");
            
    }
}

?>
