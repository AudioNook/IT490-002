<?php
require_once(__DIR__ . "/../functions.php");
require_once(__DIR__ . "/../../../vendor/autoload.php");

function handle_logout($jwt){
    $db = $db = getDB();
    $table_name= 'JWT_Sessions';
    $query= "DELETE FROM $table_name WHERE token = :token";
    $stmt = $db->prepare($query);
    try{
    $stmt->execute([
        ':token' => $jwt
        ]);
        return [
            'type' => 'logout',
            'code' => 200,
            'status' => 'success',
            'message' => 'Deleted user session.',
                ];
    }
    catch(PDOException $e){
        $error_message = var_export($e, true);
                return [
                    'type' => 'logout',
                    'code' => 500,
                    'status' => 'error',
                    'message' => $error_message,
                ];
    }
}