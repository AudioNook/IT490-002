<?php
require_once(__DIR__ . "/functions.php");
require_once(__DIR__ . "/config.php");

function get_market($rbMQCOL){
    $req_market = array();
    $req_market['type'] = 'req_market';
    $req_market['message'] = 'Requesting ENTIRE MARKETPLACE PLEASE';
    $response = json_decode($rbMQCOL->send_request($req_market), true);

    switch ($response['code']) {
        case 200:
            var_dump($response);
            return $response;
        case 401:
            $error_msg = 'Unauthorized: ' . $response['message'];
            error_log($error_msg);
            break;
        default:
            $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
            error_log($error_msg);
            break;

    }
    return $response;
    

}