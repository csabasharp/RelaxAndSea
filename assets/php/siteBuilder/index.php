<?php

class siteBuilder
{

    //készítette Kiss Máté Dominik
    private bool $inhead;
    private bool $inbody;

    function __construct()
    {
        define("BUILT_BY_PHP",true);
       #define("DEFAULT_FOLDER","v1struktura");
        $this->inhead = false;
        $this->inbody = false;
    }

    public function requirePart(string $fajl)
    {
        require "parts/$fajl";
    }

    public function loadPage(string $fajl)
    {
        require "page/$fajl";
    }

    public function toPlace(string $location = null) : string
    {
        $ret = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        if (defined("DEFAULT_FOLDER")) {
            $ret .= "/".constant("DEFAULT_FOLDER")."/";
        }
        else {
            $ret .= "/";
        }
        if (isset( $location )&&!is_null($location)) {
            $ret .= $location;
        }
        return $ret;
    }

    public function headStart(string $lang = "hu")
    {
        if ($this->inhead === true) {
            throw new Exception("Already in head!");
        }
        if ( $this->inbody === true) {
            throw new Exception("Already in body!");
        }
        $this->inhead = true;

        $start = "<!DOCTYPE html>";
        $start .= "<html lang='".$lang."'>";
        print $start;
    }

    public function headStop(array $params = null) : string // ötlet: array(array(érték)) tömbben, tag alapján tömb, értékkekkel
    {
        if ($this->inhead === false) {
            throw new Exception("Not yet in head!");
        }
        if ( $this->inbody === true) {
            throw new Exception("Already in body!");
        }
        $this->inhead = false;

        $head = "<head>";
        $head .= require "parts/head.php";
        $head .= "</head>";
        return $head;
    }

    public function bodyStart($page = "home") : string
    {
        if ($this->inhead === true) {
            throw new Exception("Already in head!");
        }
        if ( $this->inbody === true) {
            throw new Exception("Already in body!");
        }
        $this->inbody = true;

        $navbar .= "<body>";
        $navabar = require "parts/navbar.php";
        return $navbar;
    }

    public function bodyStop() : string
    {
        if ($this->inhead === true) {
            throw new Exception("Already in head!");
        }
        if ( $this->inbody === false) {
            throw new Exception("Not yet in body!");
        }
        $this->inbody = false;

        $footer = require "parts/footer.php";
        $footer .= "</body></html>";
        return $footer;
    }

}


?>
