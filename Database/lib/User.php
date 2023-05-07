<?php
namespace Database;
require_once(__DIR__ . "/db.php");
use Database\db;
require_once(__DIR__ . "/JWTSessions.php");
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
             $jwt = $session->create_db_session($user);
             if($jwt){
                return [
                    'code'=>200,
                    'message'=>' Generated user session',
                    'token' => $jwt['token'],
                    'expiry' => $jwt['expiry']
                ];
             } else{
                return [
                    'code'=>400,
                    'message'=>'unable generate user session'
                ];
             }
            }else{
                return [
                    'code'=>401,
                    'message'=>'Invalid login credentials'
                ];
            }
        }
    }

    /**
     * @param $email
     * @param $username
     * @param $password
     * @return array
     */

    public function register($email, $username, $hash, $gkey)
    {
        $table_name = 'Users';
        $query = "INSERT INTO $table_name (email, username, password, gkey) VALUES(:email, :username, :password, :gkey)";
        $params = [
            ":email" => $email,
            ":username" => $username,
            ":password" => $hash,
            ":gkey" => $gkey
        ];
        $result = $this->exec_query($query, $params);
        if ($result !== false){
            return [
                'code'=>200,
                'message'=> 'Successfully registered user'
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to register user'
            ];
        }

    }


    public function logout($jwt){
        $table = 'JWT_Sessions';
        $query = "DELETE FROM $table WHERE token = :token";
        $params = [':token' => $jwt];
        $result = $this->exec_query($query,$params);
        if ($result !== false){
            return [
                'code'=>200,
                'message'=> 'Successfully logged out'
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to logout'
            ];
        }

    }

    public function get_user_by_id($uid)
    {
        $table = 'Users';
        $query = "SELECT username, email, gkey FROM $table WHERE id = :uid";
        $params = [':uid' => (int) $uid];
        $result = $this->exec_query($query, $params);
        if ($result !== false && !empty($result)){
            return [
                'code'=>200,
                'message'=> 'Sending user id',
                'userid' => $result
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to retrieve user id'
            ];
        }
        
    }
    public function get_user_by_username($user)
    {
        $table = 'Users';
        $query = "SELECT id, username, email, gkey FROM $table WHERE username = :username or email = :username";
        $params = [':username' => $user];
        $result = $this->exec_query($query, $params);
        if ($result !== false && !empty($result)){
            return [
                'code'=>200,
                'message'=> 'Sending username',
                'username' => $result
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to retrieve username'
            ];
        }
        
    }

}