<?php

require_once(__DIR__ . "/../../vendor/autoload.php");
//use Database\Config;
//use Firebase\JWT\{JWT,Key};
use RabbitMQ\RabbitMQClient;

class DBRequests
{
    protected $rabbitMQClient;

    public function __construct()
    {
        // TODO: Make this a DB Specific Rabbitserver @jmpearson135
        $this->rabbitMQClient = new RabbitMQClient("rabbitMQ.ini", "testServer");
    }

    public function send($request)
    {
        $response = json_decode($this->rabbitMQClient->send_request($request), true);

        // Close the connection
        $this->rabbitMQClient->close();
        return $response;
    }
    public function register($username, $email, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $request = [
            'type' => 'register',
            'email' => $email,
            'username' => $username,
            'password' => $hash,
            'response' => 'Sending register request',
        ];

        $response = $this->send($request);

        switch ($response['code']) {
            case 200:
                redirect(get_url("login.php"));
                break;
            case 409:
                echo '<script language="javascript">';
                echo 'alert("' . $response['message'] . '")';
                echo '</script>';
                break;
            default:
                echo ($response['message']);
        }
    }
    public function login($username, $password)
    {
        $request = [
            'type' => 'login',
            'username' => $username,
            'password' => $password,
            'response' => 'Sending login request',
        ];

        $response = $this->send($request);

        switch ($response['code']) {
            case 200:
                $token = $response['token'];
                $expiry = $response['expiry'];
                setcookie("jwt", $token, $expiry, "/");
                redirect(get_url("marketplace.php"));
                break;
            case 401:
                echo '<script language="javascript">';
                echo 'alert("' . $response['message'] . '")';
                echo '</script>';
                break;
            default:
                echo ($response['message']);
        }
    }
    public function logout()
    {
        $jwt = $_COOKIE['jwt'];
        $request = [
            'type' => 'logout',
            'token' => $jwt,
            'message' => 'Logout request',
        ];

        $response = $this->send($request);

        switch ($response['code']) {
            case 200:
                // Remove JWT cookie
                unset($_COOKIE["jwt"]);
                setcookie("jwt", "", -1, "/");
                // Redirect to login page
                redirect("landing.php");
                break;
            case 400:
                $error_msg = 'Unauthorized: ' . $response['message'];
                error_log($error_msg);
                break;
            default:
                $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
                error_log($error_msg);
                break;
        }
    }

    public function getMarket()
    {
        $request = [
            'type' => 'get_marketplace',
            'message' => 'Requesting ENTIRE MARKETPLACE PLEASE',
        ];

        $response = $this->send($request);
        switch ($response['code']) {
            case 200:
                var_dump($response);
                return $response;
            case 401:
                $error_msg = 'Unauthorized: ' . $response['message'];
                error_log($error_msg);
                break;
            default:
                $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
                error_log($error_msg);
                break;
        }
        return $response;
    }

    public function getUserCreds($user_id)
    {
        $request = [
            'type' => 'user_cred',
            'message' => 'Sending user_creds request',
            'user_id' => (int)$user_id,
        ];

        $response = $this->send($request);
        switch ($response['code']) {
            case 200:
                return $response;
            case 401:
                $error_msg = 'Unauthorized: ' . $response['message'];
                error_log($error_msg);
                break;
            default:
                $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
                error_log($error_msg);
                break;
        }
        return $response;
    }
    public function addToCollect($user_id, $items)
    {
        $request = [
            'type' => 'add_collect',
            'message' => 'Adding to collection request',
            'user_id' => (int)$user_id,
            'items' => $items,
        ];

        $response = $this->send($request);
        echo $response;
        switch ($response['code']) {
            case 200:
                $response['success'] = true;
                break;
            case 401:
                $response['success'] = false;
                $error_msg = 'Unauthorized: ' . $response['message'];
                error_log($error_msg);
                break;
            default:
                $response['success'] = false;
                $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
                error_log($error_msg);
                break;
        }
    }
    public function getCollection($user_id)
    {
        $request = [
            'type' => 'user_collect',
            'message' => 'Sending collection request',
            'user_id' => (int)$user_id,
        ];

        $response = $this->send($request);
        switch ($response['code']) {
            case 200:
                return $response;
            case 401:
                $error_msg = 'Unauthorized: ' . $response['message'];
                error_log($error_msg);
                break;
            default:
                $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
                error_log($error_msg);
                break;
        }
        return $response;
    }

    public function getItem($user_id, $collect_id)
    {
        $request = [
            'type' => 'req_item',
            'message' => 'Requesting item from Collection',
            'user_id' => (int)$user_id,
            'collect_id' => (int)$collect_id,
        ];
        $response = $this->send($request);
        switch ($response['code']) {
            case 200:
                return $response;
            case 401:
                $error_msg = 'Unauthorized: ' . $response['message'];
                error_log($error_msg);
                break;
            default:
                $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
                error_log($error_msg);
                break;
        }
        return $response;
    }

    public function validateJWT($jwt)
    {
        $request = [
            'type' => 'validate_jwt',
            'token' => $jwt,
        ];

        return $this->send($request);
    }
    public function checkJWT()
    {
        if (isset($_COOKIE['jwt'])) {
            $jwt = $_COOKIE['jwt'];
            $response = $this->validateJWT($jwt);
            if ($response['code'] == 200) {
                return true;
            }
        }
        return false;
    }
}
