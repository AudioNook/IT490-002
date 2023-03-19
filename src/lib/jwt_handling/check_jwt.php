<?php
//header('Content-Type: application/json');
require_once(__DIR__ . "/../functions.php");
require_once(__DIR__ . "/../../../vendor/autoload.php");
require_once(__DIR__ . "/../config.php");

function check_jwt($rbMQc)
{
    if (isset($_COOKIE["jwt"]) && !empty($_COOKIE["jwt"])) {
        $jwt = $_COOKIE["jwt"];
        $jwt_req = array();
        $jwt_req['type'] = 'validate_jwt';
        $jwt_req['token'] = $jwt;
        $response = json_decode($rbMQc->send_request($jwt_req), true);
        if ($response['type'] == 'validate_jwt') {
            switch ($response['code']) {
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
}
