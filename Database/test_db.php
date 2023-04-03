#!/usr/bin/php
<?php

// DB functions
require(__DIR__ . "/RabbitMQ/path.inc");
require(__DIR__ . "/RabbitMQ/get_host_info.php");
require_once(__DIR__ . "/RabbitMQ/rabbitMQLib.php");
require_once(__DIR__ . "/lib/DB_utils.php");
use RabbitMQ\rabbitMQClient;
$rabbit_ini = "rabbitMQ.ini";
$rabbit_server = "testServer";
// Instantiate the rabbitMQClient class
echo "RabbitMQClient: Starting client..." . PHP_EOL;
$client = new rabbitMQClient($rabbit_ini, $rabbit_server);

// Call the send_request function with the message to be sent
$request = array(
    'type'=> 'login',
    'username' => 'test_user',
    'password' => 'test');
$response = $client->send_request($request);

// Process the response received from the server
echo "Response: ".$response . PHP_EOL;
var_dump($response);

$client->close();
exit();
