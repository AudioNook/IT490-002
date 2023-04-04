<?php
namespace Database;
require_once(__DIR__ . "/db.php");
use Database\db;
require_once(__DIR__ . "/sessions.php");
use Database\JWTSessions;

/**
 * Class User: Database class for users
 * @package Database
 */
class User extends db
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $username
     * @param $password
     * @return array
     */
    public function login($username, $password){
        $table_name = 'Users';
        $query = "SELECT id, username, email, password FROM $table_name WHERE username = :username or email = :username";
        $params = [':username' => $username];
        $user = $this->exec_query($query,$params);
        if($user){
            $user = $user[0];
            $hash = $user["password"];
            unset($user["password"]);
            if (password_verify($password, $hash)) {
             $session = new JWTSessions();
             $jwt=$session->create_db_session($user);
             if($jwt){
                return [
                    'success'=>true,
                    'message'=>' Generated user session',
                    'token' => $jwt['token'],
                    'expiry' => $jwt['expiry']
                ];
             } else{
                return ['success'=>false,'message'=>'unable generate user session'];
             }
            }else{
                return ['success'=>false,'message'=>'Invalid login credentials'];
            }
        }
    }

    /**
     * @param $email
     * @param $username
     * @param $password
     * @return array
     */
    public function register($email, $username, $hash)
    {
        $table_name = 'Users';
        $query = "INSERT INTO $table_name (email, username, password) VALUES(:email, :username, :password)";
        $params = [
            ":email" => $email,
            ":username" => $username,
            ":password" => $hash
        ];

        return $this->exec_query($query, $params);
    }
    public function logout($jwt){
        $table = 'JWT_Sessions';
        $query = "DELETE FROM $table WHERE token = :token";
        $params = [':token' => $jwt];
        return $this->exec_query($query,$params);
    }

    public function get_user_by_id($uid)
    {
        $table = 'Users';
        $query = "SELECT username, email FROM $table WHERE id = :uid";
        $params = [':uid' => (int) $uid];
        return $this->exec_query($query, $params);
    }
    public function get_user_by_username($user)
    {
        $table = 'Users';
        $query = "SELECT id, username, email FROM $table WHERE username = :username or email = :username";
        $params = [':username' => $user];

        return $this->exec_query($query, $params);
    }

}