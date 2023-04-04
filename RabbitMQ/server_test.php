#!/usr/bin/php
<?php
require_once(__DIR__ . "/../vendor/autoload.php");
use RabbitMQ\RabbitMQServer;
$rabbit_ini = "rabbitMQ.ini";
$rabbit_server = "testServer";
error_reporting(E_ALL ^ E_DEPRECATED);

function requestProcessor($request)
{
    echo "========================".PHP_EOL;
    echo "RECEIVED REQUEST". PHP_EOL;
    echo json_encode($request, JSON_PRETTY_PRINT) . PHP_EOL;
    if(!isset($request['type']))
    {
        return "ERROR: unsupported message type";
    }

    switch ($request['type']) {
        // testing
        case "test": 
            $response = array("type" => "test", "code" => '200', "status" => "success", 'message' => "It went through");
            break;
        default:
            $response = array("type" => "default", "code" => '204', "status" => "success", 'message' => "Server received request and processed");
            break;
    }

    echo "\n";
    echo "SENDING RESPONSE..." . PHP_EOL;
    echo json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL;

    return json_encode($response);
}
$server = new RabbitMQServer($rabbit_ini, $rabbit_server);

echo "RabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "RabbitMQServer END".PHP_EOL;
exit();
?>