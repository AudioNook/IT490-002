<?php
require_once(__DIR__ . "/../functions.php");

function db_login($username, $password)
{
    $table_name = 'Users';
    $query = "SELECT id, username, email, password FROM $table_name WHERE username = :username or email = :username";
    $params = [':username' => $username];

    // Call executeQuery() function to execute the query and pass the parameters
    $user = executeQuery($query, $params);

    if ($user !== false) {
        $user = $user[0];
        $hash = $user["password"];
        unset($user["password"]);

        if (password_verify($password, $hash)) {
            $jwt = generate_jwt($user);
            if ($jwt['status'] !=='error') {
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
                    'message' => 'Unable to generate User Session'
                ];
            }
        } else {
            return [
                'type' => 'login',
                'code' => 401,
                'status' => 'error',
                'message' => 'Wrong password'
            ];
        }
    } else {
        return [
            'type' => 'login',
            'code' => 401,
            'status' => 'error',
            'message' => 'Username not found'
        ];
    }
}
function db_logout($jwt)
{
    require_once(__DIR__ . "/../../../vendor/autoload.php");
    $table_name = 'JWT_Sessions';
    $query = "DELETE FROM $table_name WHERE token = :token";
    $params = [':token' => $jwt];
    $result = executeQuery($query, $params);
    if ($result !== false) {
        return [
            'type' => 'logout',
            'code' => 200,
            'status' => 'success',
            'message' => 'Deleted user session.',
        ];
    } else {
        return [
            'type' => 'logout',
            'code' => 500,
            'status' => 'error',
            'message' => 'Error deleting user session.',
        ];
    }
}
function db_register($email, $username, $password)
{
    $db = getDB();
    $table_name = 'Users';
    $query = "INSERT INTO $table_name (email, username, password) VALUES(:email, :username, :password)";
    $stmt = $db->prepare($query);

    try { // maps username to username and password to password
        $stmt->execute([":email" => $email, ":username" => $username, ":password" => $password]);
        //echo "User registered: $username";
        return [
            'type' => 'register',
            'code' => 200,
            'status' => 'success',
            'message' => 'Registered user: ' .  $username
        ];
    } catch (PDOException $e) {
        //$e = json_decode($e,true);
        // checks for error num for duplicate
        if ($e->getCode() === "23000") {
            preg_match("/Users.(\w+)/", $e->getMessage(), $matches);
            if (isset($matches[1])) { // if duplicate error look for username
                echo $matches[1]  . " is already in use!";
                return [
                    'type' => 'register',
                    'code' => 409,
                    'status' => 'error',
                    'message' => $matches[1]  . " is already in use!"
                ];
            }
        } else {
            $error_message = var_export($e, true);
            return [
                'type' => 'register',
                'code' => 500,
                'status' => 'error',
                'message' => $error_message,
            ];
        }
    }
    function db_credentials($user_id){
        $table_name = 'Users';
        $query = "SELECT username, email FROM $table_name WHERE id = :uid";
        $params = [':uid' => $user_id];
        $user = executeQuery($query, $params);
        if ($user !== false) {
            return [
                'type' => 'user_cred',
                'code' => 200,
                'status' => 'success',
                'username' => $user['username'],
                'email' => $user['email'],
                'message' => 'Sucessfully returning usernam and email',
            ];
        } else {
            return [
                'type' => 'user_cred',
                'code' => 500,
                'status' => 'error',
                'message' => 'Unable to return username and email',
            ];
        }
    }
}

