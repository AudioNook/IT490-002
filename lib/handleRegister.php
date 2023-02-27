<?php


function handleRegister($username,$password){
        require_once(__DIR__ . "/functions.php");
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO testusers (username, password) VALUES(:username, :password)');

            try{ // maps username to username and password to password
                $stmt->execute([":username" => $username, ":password" => $password]);
                echo "User registered: $username";
                return "valid";        
                
            }
            catch (Exception $e){
                //$e = json_decode($e,true);
                // checks for error num for duplicate
                if ($e[1] === 1062){ 
                    preg_match("/testusers.(\w+)/", $e[2], $matches); 
                    if (isset($matches[1])) { // if duplicate error look for username
                    echo $matches[1]  . " is already in use!";
                    return "duplicate";
                    }
                } else { // else we dont know what happened but maybe this'll give us a hint
                    echo error_log(var_export($e, true));
                    return "error";
                }
                
            }
}

?>