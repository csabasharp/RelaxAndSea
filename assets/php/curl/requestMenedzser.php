<?php

class RequestMenedzser
{
    private $curl;
    private $url;
    private $options;

    function __construct(string $url = null)
    {
        $this->curl = curl_init();
        if (is_null($url)) {
            $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/api/";
        }
        $this->url = $url;
        $this->options = array(
            CURLOPT_RETURNTRANSFER => true
        );
    }

    function close()
    {
        if (isset($this->curl) && !is_null($this->curl)) {
            curl_close($this->curl);
        }
    }

    function apiCreate(string $table,int $id = null, $params = array())
    {
        $defaults = array(

            CURLOPT_URL => $this->url.$table.(!is_null($id))? "/".$id : "", 
            
            CURLOPT_POST => true,

            CURLOPT_POSTFIELDS => $params
            
        );
        curl_setopt_array($this->curl, ($defaults + $this->options));

        $result = curl_exec($this->curl);
        $result = json_decode($result);

        return $result;
    }

    function apiRead(string $table,int $id = null, $params = array())
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->url.$table.(!is_null($id))? "/".$id : "");
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        /*$defaults = array(

            CURLOPT_URL => $this->url.$table.(!is_null($id))? "/".$id : "",
            
            CURLOPT_CUSTOMREQUEST => "GET",

            CURLOPT_POSTFIELDS => $params
            
        );
        curl_setopt_array($this->curl, ($defaults + $this->options));*/

        try {
            $result = curl_exec($this->curl);
            # $result = json_decode($result);
        } catch (\Throwable $th) {
            throw $th;
        }

        return $result;
    }

    function apiUpdate(string $table,int $id = null, $params = array())
    {
        $defaults = array(

            CURLOPT_URL => $this->url.$table.(!is_null($id))? "/".$id : "",
            
            CURLOPT_PUT => true,
            
            CURLOPT_POSTFIELDS => $params,

            CURLOPT_RETURNTRANSFER => true
            
        );
        curl_setopt_array($this->curl, $defaults);

        $result = curl_exec($this->curl);
        $result = json_decode($result);

        return $result;
    }
}

?>