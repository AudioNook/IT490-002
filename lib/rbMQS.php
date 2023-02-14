<?php
require_once(__DIR__ . "/path.inc");
require_once(__DIR__ . "/get_host_info.inc");
require_once(__DIR__ . "/rabbitMQLib.inc");

function get_rbMQS(){
    global $rbMQS;

    if(!isset($rbMQS)){
        try{
            require_once(__DIR__ . "/config.php");
            $rbMQC = new rabbitMQServer($rabbit_ini,$rabbit_server);
        }
        catch(Exception $e){
            error_log("get_rbMQC() error: " . var_export($e,true));
			$rbMQC = null;
        }
    }
}

?>