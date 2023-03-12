<?php
require_once(__DIR__ . "/path.inc");
require_once(__DIR__ . "/get_host_info.inc");
require_once('rabbitMQLib.inc');

function get_rbMQc(){
    global $rbMQc;

    if(!isset($rbMQc)){
        try{
            require_once(__DIR__ . "/config.php");
            $rbMQc = new rabbitMQClient($rabbit_ini,$rabbit_server);
        }
        catch(Exception $e){
            error_log("get_rbMQC() error: " . var_export($e,true));
			$rbMQc = null;
        }
    }

    return $rbMQc;
}