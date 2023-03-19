<?php
//header('Content-Type: application/json');
require_once(__DIR__ . "/../functions.php");
require_once(__DIR__ . "/../../../vendor/autoload.php");
require_once(__DIR__ . "/../config.php");

function delete_jwt() {
    // Get JWT from cookie
    $jwt = $_COOKIE["jwt"];
    global $rbMQc;
    $logout_req = array();
    $logout_req['type'] = 'logout';
    $logout_req['token'] = $jwt;
    $response = json_decode($rbMQc->send_request($logout_req), true);
    if($response['type'] == 'logut'){
        switch($response['code']){
            case 200:
                // Remove JWT cookie
                unset($_COOKIE["jwt"]);
                setcookie("jwt", "", -1, "/");
                // Redirect to login page
                redirect("login.php");
                break;
            case 500:
                error_log($response['message']);
                break;
            default:
                echo "idk what happened bro";
        }
    }
}
?>
