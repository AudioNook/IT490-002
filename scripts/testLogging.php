#!/usr/bin/php
<?php
// my functions
require(__DIR__ . "/../src/lib/functions.php");
    $rbMQLc = get_rbMQLc();

    $msg = "ERROR MIDTERM LATE";
    $type = 'database';
    $errorFileName =" ERROR IN FILE ". __FILE__. " :\n";
    $line = "ERROR ON LINE: ".__LINE__." \n";
    $error = array();
    $error['type'] = $type;
    $error['message'] = $msg;
    $error['file'] = $errorFileName;
    $error['line'] = $line;
    $rbMQLc->publish($error);

?>
