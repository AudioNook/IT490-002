#!/usr/bin/php
<?php
// my functions
require(__DIR__ . "/../src/lib/functions.php");



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
        // Handling reviews + new reviews
        case "reviews":
            $response = handle_review($request);
            break;
        case "new_review":
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
        case "add_collect":
            $response = db_add_collect($request['user_id'],$request['items']);
            break;
        case "user_collect":
            $response = db_user_collect($request['user_id']);
            break;
        case "list_item":
            $response = db_list_item($request['uid'],$request['cid'],$request['condition'],$request['description'],$request['price']);
            break;
        case "req_cart":
            $response = db_cart($request);
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
$rbMQS = get_rbMQs();

echo "RabbitMQServer BEGIN".PHP_EOL;
$rbMQs->process_requests('requestProcessor');
echo "RabbitMQServer END".PHP_EOL;
exit();
?>