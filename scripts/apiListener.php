#!/usr/bin/php
<?php
// my functions
require(__DIR__ . "/../src/lib/functions.php");



function requestProcessor($request)
{
    echo "========================".PHP_EOL;
    echo "RECEIVED ".$request['type'] . " REQUEST". PHP_EOL;
    echo json_encode($request, JSON_PRETTY_PRINT) . PHP_EOL;
    if(!isset($request['type']))
    {
        return "ERROR: unsupported message type";
    }

    switch ($request['type']) {
        // Performs the curl to search the api for the information the user requested.
        case "search":
            $response = searchApi($request);
              break;
        default:
            $response = array("type" => "default", "code" => '204', "status" => "success", 'message' => "Server received request and processed");
            break;
        }
    echo "\n";
    echo "SENT RESPONSE" . PHP_EOL;
    echo json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL;

    return json_encode($response);
    echo "RESPONSE WAS SENT";
}

$rbMQAPIS = get_apiServer();

echo "API SERVER START".PHP_EOL;
$rbMQAPIS->process_requests('requestProcessor');
echo "RabbitMQServer END".PHP_EOL;
exit();
?>