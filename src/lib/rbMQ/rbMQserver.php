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

?>