<?php
// my functions
require_once(__DIR__ . "/../functions.php");
     
    /* 
    $rbMQLc = get_rbMQLc();
    date_default_timezone_set('US/Eastern'); //sets the timezone to EST
    $t=time();
    $currenttime = (date("m-d-Y",$t). "  " . date('h:i:s')); //Gets the Month/Day/Year and Hours Minutes Seconds
    $eType = 'db'; //Sets the type of error
    $msg = "Sending Error Logs";
    $errorFileName ="  ERROR IN FILE ". __FILE__. ": "; // Tells us the name of the file there was an error in
    $line = "ON LINE: ".__LINE__." \n"; //Tells us the line number the error is on
    $errorMsg = "TASK FAILED SUCCESSFULLY! \n";
    $error = array();
    $error['seperator1'] = "-----------------------------------------------------------------------------------\n";
    $error['type'] = $eType;
    $error['dtStamp'] = $currenttime;
    $error['file'] = $errorFileName;
    $error['line'] = $line;
    $error['error'] = $errorMsg ;//PLACEHOLDER FOR ERROR
    $error['seperator2'] = "-----------------------------------------------------------------------------------\n";
    $rbMQLc->publish($error);
    */
    function logIT($eType,$errorMsg, $lNum, $fName)
    {
        $rbMQLc = get_rbMQLc();
        date_default_timezone_set('US/Eastern'); //sets the timezone to EST
        $t=time();
        $currenttime = ("\n" . date("m-d-Y",$t). "  " . date('h:i:s') . "\n"); //Gets the Month/Day/Year and Hours Minutes Seconds
        $errorFileName ="ERROR IN FILE ". $fName. ": "; // Tells us the name of the file there was an error in
        $line = "ON LINE: ".$lNum." \n"; //Tells us the line number the error is on
        $errorMsgFMT = $errorMsg . "\n";
        $message = $errorFileName . "\n" . $line;
        $label = "ERROR: \n";
        $error = array();
        $error['seperator1'] = "-----------------------------------------------------------------------------------\n";
        $error['type'] = $eType;
        $error['dt'] = $currenttime;
        $error['fileLine'] = $message;
        $error['label']= $label;
        $error['error'] = $errorMsgFMT;
        $error['seperator2'] = "-----------------------------------------------------------------------------------\n";
        $rbMQLc->publish($error);

    }
    
?>
