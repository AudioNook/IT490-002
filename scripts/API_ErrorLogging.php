#!/usr/bin/php
<?php
// my functions
require(__DIR__ . "/../src/lib/functions.php");

function logError($e, $fname){
    $file = fopen( $fname . '.txt', "a" );
    foreach ($e as $errors)
    {
      fwrite($file, $errors);
    }
}



function requestProcessor($error)
{
    echo "received request".PHP_EOL;
    var_dump($error);
    if(!isset($error['type']))
    {
        return "ERROR: unsupported message type";
    }
    switch ($error['type'])
    {
        case "frontend":
            //uses logError function to write the errors to the file frontEndErrors
             logError($error, __DIR__ . "/../data/logs/frontendErrors");
             break;
                       
        case "database":
            //uses logError function to write the errors to the file dbErrors
             logError($error, __DIR__ . "/../data/logs/dbErrors");
             break;
        case "api":
            //uses logError function to write the errors to the file apiErrors    
             logError($error, __DIR__ . "/../data/logs/apiErrors");
             break;
        case 'rbmq':
            //uses logError function to write the errors to the file apiErrors    
            logError($error, __DIR__ . "/../data/logs/apiErrors");
            break;

        default:
            //uses logError function to write any errors that do not fall under the above types to a 
            // miscErrors file.    
            logError($error, __DIR__ . "/../data/logs/miscErrors");
            break;
    }
    return array("returnCode" => '0', 'response'=>"Server received request and processed");
}
$rbMQLS = get_logServer("APILogServer");

echo "Log Server Starting...".PHP_EOL;
$rbMQLS->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>
