<?php
require_once(__DIR__ . "/../../data/RabbitMQ/rbMQconfig.php");

function get_rbMQs(){
    global $rbMQs;

    if(!isset($rbMQs)){
        try{
            require_once(__DIR__ . "/config.php");
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