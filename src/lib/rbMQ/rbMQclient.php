<?php

require_once(__DIR__ . "/../../../data/RabbitMQ/rbMQconfig.php");
global $rbMQc;
global $rabbit_ini;
global $rabbit_server;

function get_rbMQLc(){
    global $rbMQLc;

    if(!isset($rbMQLc)){
        try{
            require_once(__DIR__ . "/../config.php");
            global $rabbit_ini;
            global $rabbit_FELS;
            $rbMQLc = new rabbitMQClient($rabbit_ini,$rabbit_FELS);
        }
        catch(Exception $e){
            error_log("get_rbMQC() error: " . var_export($e,true));
			$rbMQLc = null;
        }
    }

    return $rbMQLc;
}
global $rbMQCJWT;



    if(!isset($rbMQCJWT)){

        try{

            $rbMQCJWT = new rabbitMQClient($rabbit_ini,$jwtServer);

        }

        catch(Exception $e){

            error_log("get_rbMQC() error: " . var_export($e,true));

			$rbMQCJWT = null;

        }

    }
    if(!isset($rbMQCOL)){

        try{

            $rbMQCOL = new rabbitMQClient($rabbit_ini,$OLServer);

        }

        catch(Exception $e){

            error_log("get_rbMQC() error: " . var_export($e,true));

			$rbMQCOL = null;

        }

    }
    global $rbMQapiC;
    if(!isset($rbMQapiC)){

        try{

            $rbMQapiC = new rabbitMQClient($rabbit_ini,$apiServer);

        }

        catch(Exception $e){

            error_log("get_rbMQC() error: " . var_export($e,true));

			$rbMQapiC = null;

        }

    }
if (!isset($rbMQc) || $rbMQc === null) {
    $rbMQc = new rabbitMQClient($rabbit_ini,$rabbit_server);
}



/*function get_rbMQc(){
    static $rbMQc;

    if(!isset($rbMQc)){
        try{
            global $rabbit_ini;
            global $rabbit_server;
            $rbMQc = new rabbitMQClient($rabbit_ini,$rabbit_server);
        }
        catch(Exception $e){
            error_log("get_rbMQC() error: " . var_export($e,true));
			$rbMQc = null;
        }
    }

    return $rbMQc;
}

class RabbitMQClientSingleton extends rabbitMQClient
{
    private static $instance = null;

    private function __construct()
    {
        global $rabbit_ini;
        global $rabbit_server;
        parent::__construct($rabbit_ini, $rabbit_server);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function sendRequest($message)
    {
        $response = json_decode(parent::send_request($message), true);

        return $response;
    }
}
$rbMQc = RabbitMQClientSingleton::getInstance();*/