<?php


function handleLogin($username,$password){
        require_once(__DIR__ . "/functions.php");
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, password FROM testusers WHERE username = :username");

            try{
                $r = $stmt->execute([":username" => $username]);
                if($r){
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($user){
                        $pass = $user["password"];
                        if($pass == $password){
                            $p_user = print_r($user, true);
                            echo "User found: \n" . $p_user;
                            return "valid";
                        } else {
                            echo "Wrong paswword. Politely FUCK OFF";
                            return "invalid_pass";
                        } 
                    }else {
                        echo "Username not not found. Politely FUCK OFF";
                        return "invalid_user";
                    }
                }
            }
            catch (Exception $e){
                return 'var_export($e, true)';
            }
}

?>