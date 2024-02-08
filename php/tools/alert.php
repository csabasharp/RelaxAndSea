<?php

    class Alert
    {
        private $dev;
        public function __construct()
        {
            require "./../conf/init.php";
            $this->dev = $dev;
        }
        
        public function errorBuild(string $message, Exception $e = null) : string
        {
            $return = "<span class='alert error'>".$message;
            if ($this->dev && !is_null($e)) {
                $return .= ": ".$e->getMessage();
            }
            $return .= "</span>";
            return $return;
        }
    
        public function alertBuild(string $message, string $type = "") : string
        {
            $return = "<span class='alert ".$type."'>".$message."</span>";
            return $return;
        }
    }
    

?>