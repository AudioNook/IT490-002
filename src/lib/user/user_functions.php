<?php
require_once(__DIR__ . "/../functions.php");
require_once(__DIR__ . "/../../../vendor/autoload.php");
require_once(__DIR__ . "/../config.php");
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

function get_cart($user_id, $rbMQCOL){
    $get_cart = array();
    $get_cart['type'] = 'req_cart';
    $get_cart['message'] = 'Requesting item from Collection';
    $get_cart['user_id']= (int) $user_id;
    //error_log("HELLO");
    $response = json_decode($rbMQCOL->send_request($get_cart), true);

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
function update_cart($action, $user_id, $cart_id = null, $product_id = null, $rbMQc){
    $get_cart = array();
    $get_cart['type'] = 'req_cart';
    $get_cart['message'] = 'Requesting item from Collection';
    $get_cart['user_id']= (int) $user_id;
    if(!is_null($cart_id)){
        $get_cart['cart_id'] = (int) $cart_id;
        
    }
    if(!is_null($product_id)){
        $get_cart['product_id']= (int) $product_id;
    }
    $get_cart['action'] = $action;
    //error_log("HELLO");
    $response = json_decode($rbMQc->send_request($get_cart), true);

    switch ($response['code']) {
        case 200:
            error_log($response['message']);
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

/*function list_item($uid, $cid, $conditon, $description, $price, $rbMQc){
    $list_item= array();
    $list_item['type'] = 'list_item';
    $list_item['uid'] = $uid;
    $list_item['cid'] = $cid;
    $list_item['conidtion'] = $conditon;
    $list_item['description'] = $description;
    $list_item['price'] = $price;
    $response = json_decode($rbMQc->send_request($list_item), true);
    switch ($response['code']) {
        case 200:
            error_log($response['message']);
            //return true;
            break;
        case 401:
            $error_msg = 'Unauthorized: ' . $response['message'];
            error_log($error_msg);
            //window.alert($error_msg);
            break;
        default:
            $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
            error_log($error_msg);
            //alert($error_msg);
            break;

    }
    
}*/
