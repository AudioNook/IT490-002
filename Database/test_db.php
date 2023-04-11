#!/usr/bin/php
<?php

// DB functions
require_once(__DIR__ . "/../vendor/autoload.php");
use RabbitMQ\RabbitMQClient;
$rabbit_ini = "rabbitMQ.ini";
$rabbit_server = "testServer";
// Instantiate the rabbitMQClient class
echo "RabbitMQClient: Starting client..." . PHP_EOL;
$client = new RabbitMQClient($rabbit_ini, $rabbit_server);

// Call the send_request function with the message to be sent
$request = array(
    'type'=> 'create_review',
    'id'=> '3',
    'user_id'=> '1',
    'product_id'=> '2',
    'comment'=> 'wonderful',
    'created'=> '2023-03-23 22:52:07'
);
$response = $client->send_request($request);

// Process the response received from the server
echo "Response: ".$response . PHP_EOL;
var_dump($response);
$client->close();
exit();