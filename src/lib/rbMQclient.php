<?php
require_once(__DIR__ . "/../../data/RabbitMQ/rbMQconfig.php");

global $rbMQc;
global $rabbit_ini;
global $rabbit_server;

function get_rbMQc(){
    global $rbMQc;

    if(!isset($rbMQc)){
        try{
            $rbMQc = new rabbitMQClient($rabbit_ini,$rabbit_server);
        }
        catch(Exception $e){
            error_log("get_rbMQC() error: " . var_export($e,true));
			$rbMQc = null;
        }
    }

    return $rbMQc;
}