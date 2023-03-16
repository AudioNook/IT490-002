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
             logError($error, '/var/www/sample/logs/frontEndErrors');
             break;
                       
        case "db":
            //uses logError function to write the errors to the file dbErrors
             logError($error, '/var/www/sample/logs/dbErrors');
             break;
        case "api":
            //uses logError function to write the errors to the file apiErrors    
             logError($error, '/var/www/sample/logs/apiErrors');
             break;
        default:
            //uses logError function to write any errors that do not fall under the above types to a 
            // miscErrors file.    
            logError($error, '/var/www/sample/logs/miscErrors');
            break;
    }
    return array("returnCode" => '0', 'response'=>"Server received request and processed");
}
$rbMQLS = get_logServer("carlosLogServer");

echo "Log Server Starting...".PHP_EOL;
$rbMQLs->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>
