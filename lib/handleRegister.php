<?php


function handleRegister($username,$password){
        require_once(__DIR__ . "/functions.php");
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO testusers (username, password) VALUES(:username, :pass)');

            try{
                $r = $stmt->execute([":username" => $username, ":password" => $password]);
                
                    echo "User registered: \n";
                    return "valid";
                        
                
            }
            catch (Exception $e){
                //$e = json_decode($e,true);
                preg_match("/testusers.(\w+)/",$e[2], $matches);
                if(isset($matches[1]))
                {
                    echo $matches[1] . "is already in use!";
                    return "";
                }
                else
                {
                    echo error_log(var_export($e, true));
                    return "error";
                }
                
            }
}

?>