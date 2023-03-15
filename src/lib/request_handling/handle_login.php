<?php
require_once(__DIR__ . "/../functions.php");

function handle_login($username,$password){
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, password FROM testusers WHERE username = :username");

            try{
                $r = $stmt->execute([":username" => $username]);
                if($r){
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($user){
                        $pass = $user["password"];
                        if($pass == $password){
                            $jwt = generate_jwt($db,$user);
                            return [
                                'code' => 200,
                                'status' => 'success',
                                'message' => 'Valid login credentials.',
                                'token' => $jwt['token'],
                                'expiry' => $jwt['expiry']
                                    ];
                        } else {
                            return [
                                'code' => 401,
                                'status' => 'error',
                                'message' => 'Wrong password'
                            ];
                        } 
                    }else {
                        return [
                            'code' => 401,
                            'status' => 'error',
                            'message' => 'Username not found'
                        ];
                    }
                }
            }
            catch (Exception $e){
                $error_message = var_export($e, true);
                return [
                    'code' => 500,
                    'status' => 'error',
                    'message' => $error_message,
                ];
            }
}

?>