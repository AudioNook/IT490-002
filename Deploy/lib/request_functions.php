<?php
require_once(__DIR__ . "/functions.php");
require_once(__DIR__ . "/config.php");

function get_market($rbMQc){
    $req_market = array();
    $req_market['type'] = 'get_marketplace';
    $req_market['message'] = 'Requesting ENTIRE MARKETPLACE PLEASE';
    $response = json_decode($rbMQc->send_request($req_market), true);

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

function logout_request(){
    $rbMQc = rbmqc_db();
    $jwt = $_COOKIE['jwt'];
    $logout_req = array();
    $logout_req['type'] = 'logout';
    $logout_req['token'] = $jwt;
    $logout_req['message'] = 'Logging out request';

    $response = json_decode($rbMQc->send_request($logout_req), true);
    $rbMQc-> close();

    switch ($response['code']) {
        case 200:
            // Remove JWT cookie
            unset($_COOKIE["jwt"]);
            setcookie("jwt", "", -1, "/");
            // Redirect to login page
            redirect("landing.php");
           break;
        case 400:
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