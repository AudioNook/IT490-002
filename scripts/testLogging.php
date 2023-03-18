#!/usr/bin/php
<?php
// my functions
require(__DIR__ . "/../src/lib/functions.php");
    $rbMQLc = get_rbMQLc();

    $msg = "Sending Error Logs";
    
    $errorFileName =" ERROR IN FILE ". __FILE__. " :\n";
    $line = "ERROR ON LINE: ".__LINE__." \n";
    $error = array();
    $error['type'] = 'db';
    $error['file'] = $errorFileName;
    $error['line'] = $line;
    $error['error'] = "ERROR ERROR ERROR\n" ;//PLACEHOLDER FOR ERROR
    $rbMQLc->publish($error);

?>
