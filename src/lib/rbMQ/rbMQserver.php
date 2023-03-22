<?php
require_once(__DIR__ . "/../../../data/RabbitMQ/rbMQconfig.php");
function get_rbMQs(){
    global $rbMQs;

    if(!isset($rbMQs)){
        try{
            global $rabbit_ini;
            global $rabbit_server;
            $rbMQs = new rabbitMQServer($rabbit_ini,$rabbit_server);
        }
        catch(Exception $e){
            error_log("get_rbMQC() error: " . var_export($e,true));
			$rbMQs = null;
        }
    }

    return $rbMQs;
}
function get_logServer($logServer){
    global $rbMQLs;

    if(!isset($rbMQLs)){
        try{
            global $rabbit_ini;
            require_once(__DIR__ . "/../config.php");
            $rbMQLs = new rabbitMQServer($rabbit_ini,$logServer);
        }
        catch(Exception $e){
            error_log("get_rbMQC() error: " . var_export($e,true));
			$rbMQs = null;
        }
    }

    return $rbMQLs;
}
function get_jwtServer(){

    global $rbMQJWTS;



    if(!isset($rbMQJWTS)){

        try{

            global $rabbit_ini;

            global $jwtServer;

            require_once(__DIR__ . "/../config.php");

            $rbMQJWTS= new rabbitMQServer($rabbit_ini,$jwtServer);

        }

        catch(Exception $e){

            error_log("get_rbMQC() error: " . var_export($e,true));

			$rbMQJWTS = null;

        }

    }



    return $rbMQJWTS;

}
function get_olServer(){

    global $rbMQOLS;



    if(!isset($rbMQOLS)){

        try{

            global $rabbit_ini;

            global $OLServer;

            require_once(__DIR__ . "/../config.php");

            $rbMQOLS= new rabbitMQServer($rabbit_ini,$OLServer);

        }

        catch(Exception $e){

            error_log("get_rbMQC() error: " . var_export($e,true));

			$rbMQOLS = null;

        }

    }



    return $rbMQOLS;

}
?>