<?php
require_once(__DIR__ . "/../functions.php");
require_once(__DIR__ . "/../../../vendor/autoload.php");
require_once(__DIR__ . "/../config.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generate_jwt($user){
    // Revoke older token if one exists
    $db = getDB();
    $table_name= 'JWT_Sessions';
    $delete_query = "DELETE FROM $table_name WHERE user_id = :user_id AND expires_at > NOW()";
    $stmt = $db->prepare($delete_query);
    $stmt->execute([":user_id" => $user['id']]);

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
    $stmt = $db->prepare($insert_query);
    try{
        $stmt->execute([
            ':user_id' => $user['id'],
            ':token' => $token,
            ':expires_at' => $expiry->format('Y-m-d H:i:s'),
            ':issued_at' => $issuedAt->format('Y-m-d H:i:s'),
        ]);
        return array(
            'token' => $token,
            'expiry' => $expiry->getTimestamp()
        );
    }
    catch (Exception $e){
        throw new Exception('Error saving JWT session: ' . $e->getMessage());
    }
}
function validate_jwt($jwt) {
    $key = JWT_SECRET;
    $db = getDB();
    $table_name = "JWT_Sessions";
    $query = "SELECT * FROM $table_name WHERE token = :token AND user_id = :user_id AND expires_at > NOW()";
    $stmt = $db->prepare($query);
    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));           
        // Look up the JWT in the database
        $stmt->execute([":token" => $jwt, ":user_id" => $decoded->userid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            // Check if the token is expired
            $expiry = strtotime((string) $row['expires_at']);
            if (time() < $expiry) {

                return [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Valid token.',
                        ];
            }
        }
        return [
            'code' => 401,
            'status' => 'error',
            'message' => 'invalid token.',
                ];
    } catch (Exception $e) {
        $error_message = var_export($e, true);
        return [
            'code' => 500,
            'status' => 'error',
            'message' => $error_message,
        ];
    }
}
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
