#!/usr/bin/php
<?php
// my functions
require(__DIR__ . "/../lib/functions.php");



function requestProcessor($request)
{
    echo "received request".PHP_EOL;
    var_dump($request);
    if(!isset($request['type']))
    {
        return "ERROR: unsupported message type";
    }
    switch ($request['type'])
    {
        case "login":
            //passing in username and password from the request array
            return handleLogin($request["username"],$request["password"]);
            // handleLogin() taks a username and password then queries the DB
            
        case "register":
            //passing in username and password from the request array
            return handleRegister($request["username"],$request["password"]);

    }
    return array("returnCode" => '0', 'response'=>"Server received request and processed");
}
$rbMQS = get_rbMQs();

echo "testRabbitMQServer BEGIN".PHP_EOL;
$rbMQs->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>