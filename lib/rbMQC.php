<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function get_rbMQC(){
    global $rbMQC;

    if(!isset($rbMQC)){
        try{
            require_once(__DIR__ . "/config.php");
            $rbMQC = new rabbitMQClient($rabbit_ini,$rabbit_server);
        }
        catch(Exception $e){
            error_log("get_rbMQC() error: " . var_export($e,true));
			$rbMQC = null;
        }
    }

    return $rbMQC;
}