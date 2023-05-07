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
        $this->rabbitMQClient = new RabbitMQClient("rabbitMQ.ini", "AudioDB");
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
                //var_dump($response);
                return $response['marketplace_items'];
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

    public function getItemDetails($item_id)
    {
        $request = [
            'type' => 'get_item_details',
            'message' => 'Requesting item details',
            'item_id' => (int)$item_id
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
    public function getAlbumReviews($collection_id)
    {
        $request = [
            'type' => 'get_album_reviews',
            'message' => 'Requesting album reviews',
            'collection_id' => (int)$collection_id
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
    public function reviewAlbum($user_id, $collection_id, $review, $rating)
    {
        $request = [
            'type' => 'review_album',
            'message' => 'Creating album review',
            'user_id' => (int)$user_id,
            'collection_id' => (int)$collection_id,
            'review' => $review,
            'rating' => (int)$rating
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
    public function doCart($user_id, $product_id = null, $action = null, $cart_id = null)
    {
        $request = [
            'type' => 'cart',
            'message' => 'Requesting cart',
            'user_id' => (int)$user_id
        ];

        if ($product_id != null) {
            $request['product_id'] = (int)$product_id;
        }

        if ($action != null) {
            $request['action'] = $action;
        }

        if ($cart_id != null) {
            $request['cart_id'] = (int)$cart_id;
        }

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

    public function updateCart($action, $user_id, $product_id = null, $cart_id = null)
    {
        $request = [
            'type' => 'cart',
            'message' => 'Updating cart',
            'user_id' => (int)$user_id,
            'action' => $action
        ];
        if ($cart_id != null) {
            $request['cart_id'] = (int)$cart_id;
        }
        if ($product_id != null) {
            $request['product_id'] = (int)$product_id;
        }

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

    public function getByUserId($user_id)
    {
        $request = [
            'type' => 'by_user_id',
            'message' => 'Sending user_creds request',
            'uid' => (int)$user_id,
        ];

        $response = $this->send($request);
        switch ($response['code']) {
            case 200:
                return $response['userid'][0];
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
        //echo $response;
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
        return $response;
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
            'type' => 'get_collection_item',
            'message' => 'Requesting item from Collection',
            'user_id' => (int)$user_id,
            'collection_item_id' => (int)$collect_id,
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
    public function listItem($user_id, $collect_id, $condition, $description, $price)
    {
        $request = [
            'type' => 'list_item',
            'message' => 'Requesting item from Collection',
            'uid' => (int)$user_id,
            'cid' => (int)$collect_id,
            'condition' => $condition,
            'description' => $description,
            'price' => (int)$price,
        ];
        $response = $this->send($request);
        switch ($response['code']) {
            case 200:
                error_log($response['message']);
                redirect(get_url('marketplace.php'));
                return true;
            case 401:
                $error_msg = 'Unauthorized: ' . $response['message'];
                error_log($error_msg);
                break;
            default:
                $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
                error_log($error_msg);
                break;
        }
    }
    public function validateSession()
    {
        if (isset($_COOKIE["jwt"]) && !empty($_COOKIE["jwt"])) {
            $jwt = $_COOKIE['jwt'];
            $request = [
                'type' => 'validate_jwt',
                'token' => $jwt,
                'messge' => 'Validating JWT'
            ];
            $response = $this->send($request);
            switch ($response['code']) {
                case 200:
                    error_log("check_jwt: Good little cookie");
                    break;
                case 401:
                    // Remove JWT cookie
                    unset($_COOKIE["jwt"]);
                    setcookie("jwt", "", -1, "/");
                    // Session no longer valid please log back in
                    // Redirect to login page
                    redirect("login.php");
                    break;
                default:
                    unset($_COOKIE["jwt"]);
                    setcookie("jwt", "", -1, "/");
                    error_log($response['message']);
                    redirect("login.php");
            }
        }
    }
}
