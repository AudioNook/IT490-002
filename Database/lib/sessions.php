<?php 
namespace Database;
require_once(__DIR__ . "/db.php");
use Database\db;

require_once(__DIR__ . "/config.php");
use Database\Config;

require_once(__DIR__ . "/../../vendor/autoload.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Exception;
use PDO;
/**
 * Class JWTSessions: Database class for JWT sessions
 * @package Database
 * @package Firebase\JWT\JWT & Firebase\JWT\Key
 */
class JWTSessions extends db
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * @param $user_id
     * @param $issuedAt
     * @param $expiry
     * @return string
     */
    public function generate_jwt($user_id,$issuedAt,$expiry)
    {
        // Password matches, generate JWT token
        $config= new Config();
        $key = $config->key;
        $payload = [
            'userid'=> $user_id,
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $expiry->getTimestamp()
        ];
        $token = JWT::encode($payload, $key, 'HS256');
        return $token;
    }

    public function create_db_session($user){
        $table_name= 'JWT_Sessions';
        $this->delete_db_session($user);
        $issuedAt = new DateTimeImmutable();
        $expiry = $issuedAt->modify('+1 hour');
        $token = $this->generate_jwt($user['id'],$issuedAt,$expiry);
        $insertQuery = "INSERT INTO $table_name (user_id, token, expires_at, issued_at) VALUES (:user_id, :token, :expires_at, :issued_at)";
        $insertParms = [
            ':user_id' => $user['id'],
            ':token' => $token,
            ':expires_at' => $expiry->format('Y-m-d H:i:s'),
            ':issued_at' => $issuedAt->format('Y-m-d H:i:s'),
        ];
        
       $result = $this->exec_query($insertQuery,$insertParms);
       if($result){
        return array(
            'token' => $token,
            'expiry' => $expiry->getTimestamp()
        );
       } else{
        return false;
       }
    }

    public function delete_db_session($user){
        $table_name= 'JWT_Sessions';
        $deleteQuery = "DELETE FROM $table_name WHERE user_id = :user_id AND expires_at > NOW()";
        $deleteParams = [":user_id" => $user['id']];
        $this->exec_query($deleteQuery,$deleteParams);
    }

    public function validate_session($jwt){
        $config = new Config();
        $key = $config->key;
        $table_name = "JWT_Sessions";
        $query = "SELECT * FROM $table_name WHERE token = :token AND user_id = :user_id AND expires_at > NOW()";
        $stmt = $this->prepare($query);
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
}
