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
            if ($jwt['status'] !== 'error') {
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
}
function db_credentials($user_id)
{
    $table_name = 'Users';
    $query = "SELECT username, email FROM $table_name WHERE id = :uid";
    $params = [':uid' => (int) $user_id];
    $user = executeQuery($query, $params);
    if ($user !== false && count($user) > 0) {
        $user = $user[0];
        return [
            'type' => 'user_cred',
            'code' => 200,
            'status' => 'success',
            'username' => $user['username'],
            'email' => $user['email'],
            'message' => 'Sucessfully returning username and email',
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
function db_add_collect($user_id, $items)
{
    $user_id = (int)htmlspecialchars($user_id);
    $collection = 'Collection_Items';
    $usr_collect = 'User_Collected_Items';
    $collect_query = "INSERT INTO $collection (release_id, title, cover_image, format)VALUES(:rid, :title, :img, :format)";
    $user_query = "INSERT INTO $usr_collect (user_id, collection_item_id)
    VALUES (:uid, :cid)";
    foreach ($items as $item) {
        $params1 = [
            ':rid' => (int) htmlspecialchars($item['release_id']),
            ':title' => htmlspecialchars($item['title']),
            ':img' => htmlspecialchars($item['cover_image'],ENT_QUOTES, 'UTF-8'),
            ':format' => htmlspecialchars($item['format'])
        ];
        $lastInsertId = executeQuery($collect_query, $params1, true);
        $collected_item_id = $lastInsertId;
        $params2 = [
            ':uid' => "$user_id",
            ':cid' => "$collected_item_id",
        ];
        $r = executeQuery($user_query, $params2);
    }
    if ($r !== false) {
        return [
            'type' => 'add_collect',
            'code' => 200,
            'status' => 'success',
            'message' => 'Sucessfully added to collection',
        ];
    } else {
        return [
            'type' => 'add_collect',
            'code' => 401,
            'status' => 'error',
            'message' => 'Error adding to collection',
        ];
    }
}
function db_user_collect($user_id){
    $user_id = (int)htmlspecialchars($user_id);
    $collection = 'Collection_Items';
    $usr_collect = 'User_Collected_Items';
    $query = "SELECT Collection_Items.release_id, Collection_Items.title, Collection_Items.cover_image, Collection_Items.format
    FROM User_Collected_Items
    INNER JOIN Collection_Items ON User_Collected_Items.collection_item_id = Collection_Items.id
    WHERE User_Collected_Items.user_id = :uid;";
    $params = [':uid' => "$user_id"];
    $collected = executeQuery($query, $params);
    if ($collected !== false) {
        return [
            'type' => 'user_collect',
            'code' => 200,
            'status' => 'success',
            'message' => 'Sucessfully pulled collection',
            'collection' => $collected,
        ];
    } else {
        return [
            'type' => 'user_collect',
            'code' => 401,
            'status' => 'error',
            'message' => 'Error pulling collection',
        ];
    }
}
