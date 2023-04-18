#!/usr/bin/php
<?php
error_reporting(E_ALL ^ E_DEPRECATED);

require_once(__DIR__ . "/../vendor/autoload.php");
use DMZ\{Curl};
use RabbitMQ\RabbitMQServer;
function requestProcessor($request)
{
    echo "========================".PHP_EOL;
    echo "RECEIVED REQUEST". PHP_EOL;
    echo json_encode($request, JSON_PRETTY_PRINT) . PHP_EOL;
    $dmz_curl = new Curl();
    if(!isset($request['type']))
    {
        return "ERROR: unsupported message type";
    }
    //
    switch ($request['type']) {
        // Handling request to DMZ
        case "search": // example
            try {
                $response = $dmz_curl->search($request['searchTerm'], $request['format'],$request['genre'],$request['page']);
                // process $results as needed
              } catch (\Exception $e) {
                echo 'Error: ' . $e->getMessage();
              } finally {
                $dmz_curl->close(); // close curl session
              }
            break;
        default:
            $response = array("type" => "default", "code" => '204', "status" => "success", 'message' => "Server received request and processed");
            break;
    }

    echo "\n";
    echo "SENDING RESPONSE" . PHP_EOL;
    echo json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL;

    return json_encode($response);
}
$rbMQs = new rabbitMQServer("rabbitMQ.ini","jwtServer");

echo "RabbitMQServer BEGIN".PHP_EOL;
$rbMQs->process_requests('requestProcessor');
echo "RabbitMQServer END".PHP_EOL;
exit();
?>