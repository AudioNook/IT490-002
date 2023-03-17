<?php
require_once(__DIR__ . "/../functions.php");
function handle_register($email,$username,$password){
        $db = getDB();
        $table_name= 'Users';
        $query= "INSERT INTO $table_name (email, username, password) VALUES(:email, :username, :password)";
        $stmt = $db->prepare($query);

            try{ // maps username to username and password to password
                $stmt->execute([":email" => $email, ":username" => $username, ":password" => $password]);
                //echo "User registered: $username";
                return [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Registered user: ' .  $username
                ];        
                
            }
            catch (PDOException $e){
                //$e = json_decode($e,true);
                // checks for error num for duplicate
                if ($e->getCode() === "23000"){ 
                    preg_match("/Users.(\w+)/", $e->getMessage(), $matches);
                    if (isset($matches[1])) { // if duplicate error look for username
                    echo $matches[1]  . " is already in use!";
                    return [
                        'code' => 409,
                        'status' => 'error',
                        'message' => $matches[1]  . " is already in use!"
                    ];
                    }
                } else { 
                    $error_message = var_export($e, true);
                    return [
                        'code' => 500,
                        'status' => 'error',
                        'message' => $error_message,
                    ];
                }
                
            }
}

?>