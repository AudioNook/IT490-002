<?php
require_once(__DIR__ . "/../functions.php");
require_once(__DIR__ . "/../../../vendor/autoload.php");

function handle_logout($jwt){
    $db = $db = getDB();
    $stmt = $db->prepare("DELETE FROM jwt_sessions WHERE token = :token");
    try{
    $stmt->execute([
        ':token' => $jwt
        ]);
        return [
            'code' => 200,
            'status' => 'success',
            'message' => 'Deleted user session.',
                ];
    }
    catch(Exception $e){
        $error_message = var_export($e, true);
                return [
                    'code' => 500,
                    'status' => 'error',
                    'message' => $error_message,
                ];
    }
}