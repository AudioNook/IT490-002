<?php
//header('Content-Type: application/json');
require_once(__DIR__ . "/../functions.php");
require_once(__DIR__ . "/../../../vendor/autoload.php");
require_once(__DIR__ . "/../config.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function validate_jwt($jwt) {
    $key = JWT_SECRET;
    $db = getDB();
    $table_name = "jwt_sessions";
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