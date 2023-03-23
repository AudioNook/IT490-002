#!/usr/bin/php
<?php
// my functions
require(__DIR__ . "/../src/lib/functions.php");



function requestProcessor($request)
{
    echo "========================".PHP_EOL;
    // echo "RECEIVED ".$request['type'] . " REQUEST". PHP_EOL;
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
        // Handling user_creds
        case "user_cred":
            $response = db_credentials($request['user_id']);
            break;
        // Handling reviews
        case "reviews":
            $response = handle_review($request);
            break;
        // Handling forums
        case "topics":
        case "posts":
        case "create_post":
            $response = handle_forum($request);
            break;
        case "discussion":
        case "reply":
            $response = handle_forum($request);
            break;
        case "get_item":
            $response = db_item($request['user_id'],$request=['collect_id']);
            break;
        default:
            $response = array("type" => "default", "code" => '204', "status" => "success", 'message' => "Server received request and processed");
            break;
    }

    echo "\n";
    echo "SENT RESPONSE" . PHP_EOL;
    echo json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL;

    return json_encode($response);
}
$rbMQSOL = get_olServer();

echo "RabbitMQServer BEGIN".PHP_EOL;
$rbMQSOL->process_requests('requestProcessor');
echo "RabbitMQServer END".PHP_EOL;
exit();
?>