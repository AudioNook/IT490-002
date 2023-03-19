<?php
require_once(__DIR__ . "/../functions.php");

function handle_login($username,$password){
        $db = getDB();
        $table_name = 'Users';
        $query = "SELECT id, username, email, password FROM $table_name WHERE username = :username or email = :username";
        $stmt = $db->prepare($query);

            try{
                $r = $stmt->execute([":username" => $username]);
                if($r){
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($user){
                        $hash = $user["password"];
                        unset($user["password"]);
                        if (password_verify($password, $hash)) {
                            $jwt = generate_jwt($db,$user);
                            return [
                                'type' => 'login',
                                'code' => 200,
                                'status' => 'success',
                                'message' => 'Valid login credentials.',
                                'token' => $jwt['token'],
                                'expiry' => $jwt['expiry']
                                    ];
                        } else {
                            return [
                                'type' => 'login',
                                'code' => 401,
                                'status' => 'error',
                                'message' => 'Wrong password'
                            ];
                        } 
                    }else {
                        return [
                            'type' => 'login',
                            'code' => 401,
                            'status' => 'error',
                            'message' => 'Username not found'
                        ];
                    }
                }
            }
            catch (PDOException $e){
                $error_message = var_export($e, true);
                return [
                    'type' => 'login',
                    'code' => 500,
                    'status' => 'error',
                    'message' => $error_message,
                ];
            }
}

?>