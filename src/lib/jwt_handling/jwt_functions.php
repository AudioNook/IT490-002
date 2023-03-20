<?php
require_once(__DIR__ . "/../functions.php");
require_once(__DIR__ . "/../../../vendor/autoload.php");
require_once(__DIR__ . "/../config.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
function generate_jwt($user){
    // Revoke older token if one exists
    $table_name= 'JWT_Sessions';
    $delete_query = "DELETE FROM $table_name WHERE user_id = :user_id AND expires_at > NOW()";
    executeQuery($delete_query, [":user_id" => $user['id']]);

    // Password matches, generate JWT token
    $issuedAt = new DateTimeImmutable();
    $expiry = $issuedAt->modify('+1 hour');
    $payload = [
        'userid'=> $user['id'],
        'iat' => $issuedAt->getTimestamp(),
        'exp' => $expiry->getTimestamp()
    ];
    $key = JWT_SECRET;
    $token = JWT::encode($payload, $key, 'HS256');

    // Store the JWT session in the database
    $insert_query = "INSERT INTO $table_name (user_id, token, expires_at, issued_at) VALUES (:user_id, :token, :expires_at, :issued_at)";
    $params = [
        ':user_id' => $user['id'],
        ':token' => $token,
        ':expires_at' => $expiry->format('Y-m-d H:i:s'),
        ':issued_at' => $issuedAt->format('Y-m-d H:i:s'),
    ];

    try {
        if (executeQuery($insert_query, $params)) {
            return array(
                'token' => $token,
                'expiry' => $expiry->getTimestamp()
            );
        } else {
            throw new Exception('Error saving JWT session: Failed to execute query');
        }
    } catch (Exception $e) {
        throw new Exception('Error saving JWT session: ' . $e->getMessage());
    }
}
function validate_jwt($jwt)
{
    $key = JWT_SECRET;
    $table_name = "JWT_Sessions";
    $query = "SELECT * FROM $table_name WHERE token = :token AND user_id = :user_id AND expires_at > NOW()";

    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    // Look up the JWT in the database
    $params = [":token" => $jwt, ":user_id" => $decoded->userid];
    $rows = executeQuery($query, $params);
    if ($rows && count($rows) > 0) {
        $row = $rows[0];
        // Check if the token is expired
        $expiry = strtotime((string) $row['expires_at']);
        if (time() < $expiry) {

            return [
                'type' => 'validate_jwt',
                'code' => 200,
                'status' => 'success',
                'message' => 'Valid token.',
            ];
        }
    }
    return [
        'type' => 'validate_jwt',
        'code' => 401,
        'status' => 'error',
        'message' => 'invalid token.',
    ];
}
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