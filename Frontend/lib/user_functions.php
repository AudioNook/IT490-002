<?php
 require_once(__DIR__ . "/functions.php");
require_once(__DIR__ . "/../../vendor/autoload.php");
require_once(__DIR__ . "/config.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function logged_in($redirect = false, $destination = "login.php") {
    $isLoggedIn = false;

    if (isset($_COOKIE["jwt"]) && !empty($_COOKIE["jwt"])) {
        $jwt = $_COOKIE["jwt"];
        $decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $jwt)[1]))), true);
        if (isset($decoded['exp']) && $decoded['exp'] > time()) {
            $isLoggedIn = true;
        } else {
            // Token has expired or is invalid
            unset($_COOKIE["jwt"]);
            setcookie("jwt", "", -1, "/");
        }
    }
    if ($redirect && !$isLoggedIn) {
        redirect($destination);
    }

    return $isLoggedIn;
}

function get_user_id(){
    $user_id = null;
    if (logged_in()) {
        $jwt = $_COOKIE["jwt"];
        $key = JWT_SECRET;
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        $user_id = $decoded->userid;
    }
    return $user_id;
}

function get_credentials($user_id,$rbMQc){
    $user_cred = array();
    $user_cred['type'] = 'user_cred';
    $user_cred['message'] = "Sending user_creds request";
    $user_cred['user_id'] = (int)$user_id;

    $response = json_decode($rbMQc->send_request($user_cred), true);

    switch ($response['code']) {
        case 200:
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
function get_collection($user_id,$rbMQc){
    $collect_req = array();
    $collect_req['type'] = 'user_collect';
    $collect_req['message'] = "Sending collection request";
    $collect_req['user_id'] = (int)htmlspecialchars($user_id);

    $response = json_decode($rbMQc->send_request($collect_req), true);

    switch ($response['code']) {
        case 200:
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
function get_item($user_id, $collect_id,$rbMQCOL){
    $get_item = array();
    $get_item['type'] = 'req_item';
    $get_item['message'] = 'Requesting item from Collection';
    $get_item['user_id']= (int) $user_id;
    $get_item['collect_id'] = (int) $collect_id;
    //error_log("HELLO");
    $response = json_decode($rbMQCOL->send_request($get_item), true);

    switch ($response['code']) {
        case 200:
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

function check_jwt(){
    if (isset($_COOKIE["jwt"]) && !empty($_COOKIE["jwt"])) {
        $jwt = $_COOKIE["jwt"];
        global $rbMQCJWT;
        $jwt_req = array();
        $jwt_req['type'] = 'validate_jwt';
        $jwt_req['token'] = $jwt;
        $response = json_decode($rbMQCJWT->send_request($jwt_req), true);
        switch($response['code']){
        case 200:
            error_log("check_jwt: Good little cookie");
            break;
        case 401:
            // Remove JWT cookie
            unset($_COOKIE["jwt"]);
            setcookie("jwt", "", -1, "/");
            // Session no longer valid please log back in
            // Redirect to login page
            redirect("login.php");
            break;
        default:
            unset($_COOKIE["jwt"]);
            setcookie("jwt", "", -1, "/");
            error_log($response['message']);
            redirect("login.php");
       }
    }
}