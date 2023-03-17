<?php
//header('Content-Type: application/json');
require_once(__DIR__ . "/../functions.php");
require_once(__DIR__ . "/../../../vendor/autoload.php");
require_once(__DIR__ . "/../config.php");
use Firebase\JWT\JWT;

function generate_jwt($db, $user){
    // Revoke older token if one exists
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
    $stmt = $db->prepare("INSERT INTO jwt_sessions (user_id, token, expires_at, issued_at) VALUES (:user_id, :token, :expires_at, :issued_at)");
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