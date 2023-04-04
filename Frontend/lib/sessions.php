<?php 
namespace Frontend;

function delete_jwt() {
    // Get JWT from cookie
    $jwt = $_COOKIE["jwt"];
    global $rbMQc;
    $logout_req = array();
    $logout_req['type'] = 'logout';
    $logout_req['token'] = $jwt;
    $response = json_decode($rbMQc->send_request($logout_req), true);
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