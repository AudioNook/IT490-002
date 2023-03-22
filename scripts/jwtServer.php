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
        // Handling User Sessions
        case "login":
            $response = db_login($request["username"], $request["password"]);
            break;
        case "register":
            $response = db_register($request["email"], $request["username"], $request["password"]);
            break;
        case "validate_jwt":
            $response = validate_jWT($request['token']);
            break;
        case "logout":
            $response = db_logout($request['token']);
            break;
        // Handling forums
        case "topics":
            echo "I MADE IT HERE";
            $response = handle_forum($request);
            
            break;
        //case "posts":
        //case "create_post":
        //case "discussion":
        //case "reply":
            //$response = "TESTING"/*handle_forum($request)*/;
            //echo $response;
            //break;
        //default:
            //$response = array("type" => "default", "code" => '204', "status" => "success", 'message' => "Server received request and processed");
            //echo $response;
            //break;
    }

    echo "\n";
    echo "SENT RESPONSE" . PHP_EOL;
    echo json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL;

    return json_encode($response);
    echo "RESPONSE WAS SENT";
}
$rbMQS = get_jwtServer();

echo "RabbitMQServer BEGIN".PHP_EOL;
$rbMQS->process_requests('requestProcessor');
echo "RabbitMQServer END".PHP_EOL;
exit();
?>