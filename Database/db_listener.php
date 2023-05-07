#!/usr/bin/php
<?php
error_reporting(E_ALL ^ E_DEPRECATED);

// DB functions
require_once(__DIR__ . "/../vendor/autoload.php");

use Database\{User, JWTSessions, Forums, Collection, Marketplace, Cart, Products, Reviews};
use RabbitMQ\RabbitMQServer;

function requestProcessor($request)
{
    echo "========================" . PHP_EOL;
    echo "RECEIVED REQUEST" . PHP_EOL;
    echo json_encode($request, JSON_PRETTY_PRINT) . PHP_EOL;
    if (!isset($request['type'])) {
        return "ERROR: unsupported message type";
    }
    $db_user = new User();
    $db_session = new JWTSessions();
    $db_forums = new Forums();
    $db_collection = new Collection();
    $db_market = new Marketplace();
    $db_reviews = new Reviews();
    $db_products = new Products();
    $db_cart = new Cart();

    switch ($request['type']) {
            // Handling User Login and Sessions
        case "login":
            $response = $db_user->login($request["username"], $request["password"]);
            break;
        case "register":
            $response = $db_user->register($request["email"], $request["username"], $request["password"],$request["gkey"] );
            break;
        case "logout":
            $response = $db_user->logout($request['token']);
            break;
        case "by_user_id":
            $response = $db_user->get_user_by_id($request["uid"]);
            break;
        case "by_username":
            $response = $db_user->get_user_by_username($request["username"]);
            break;
            //Handling sessions
        case "validate_jwt":
            $response = $db_session->validate_session($request['token']);
            break;
            // Handling forums
        case "topics":
            $response = $db_forums->get_topics();
            break;
        case "posts":
            $response = $db_forums->get_posts($request['topic_id']);
            break;
        case "create_post":
            $response = $db_forums->create_post($request['topic_id'], $request['user_id'], $request['title'], $request['reply_msg']);
            break;
        case "discussion":
            $response = $db_forums->get_discussion($request['post_id']);
            break;
        case "reply":
            $response = $db_forums->create_reply($request['post_id'], $request['user_id'], $request['reply_msg']);
            break;
            // Handling collections
        case "add_collect":
            $response = $db_collection->add_to_collection($request['user_id'], $request['items']);
            break;
        case "user_collect":
            $response = $db_collection->get_user_collection($request['user_id']);
            break;
        case "get_collection_item":
            $response = $db_collection->get_collection_item($request['user_id'], $request['collection_item_id']);
            var_dump($response);
            break;
        case "get_album_reviews":
            $response = $db_collection->get_album_reviews($request['collection_id']);
            break;
        case "review_album":
            $response = $db_collection->review_album($request['user_id'], $request['collection_id'], $request['review'], $request['rating']);
            break;
            // Handling Marketplace
        case "get_marketplace":
            $response = $db_market->get_marketplace();
            break;
        case "list_item":
            $response = $db_market->list_item($request['uid'], $request['cid'], $request['condition'], $request['description'], $request['price']);
            break;
        case "get_item_details":
            $response = $db_market->get_item_details($request['item_id']);
            break;
            // TODO: Add more and fix cases for Reviews, Products, Orders, and Payments
            // Handling Products
        case "products":
            $response = $db_products->get_products($request['id'], $request['name'], $request['category'], $request['stock'], $request['cost'], $request['image']);
            break;
            // Handling Reviews
        case "get_reviews":
            $response = $db_reviews->get_reviews($request['id'], $request['product_id'], $request['comment'], $request['created']);
            break;
        case "get_user_reviews":
            $response = $db_reviews->get_user_reviews($request['id'], $request['product_id'], $request['comment'], $request['created'], $request['user_id']);
            break;
        case "create_review":
            $response = $db_reviews->create_review($request['id'], $request['user_id'], $request['product_id'], $request['comment'], $request['created']);
            break;
            // Handling Orders
            // Handling Payments
            //Handling Cart
        case "cart":
            $response = $db_cart->cart($request);
            break;
        default:
            $response = array(
                "type" => "default",
                "code" => '204',
                "status" => "success",
                'message' => "Did not match any type."
            );
            break;
    }

    echo "\n";
    echo "SENDING RESPONSE" . PHP_EOL;
    echo json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL;

    return json_encode($response);
}
$rbMQs = new RabbitMQServer("rabbitMQ.ini", "AudioDB");

echo "RabbitMQServer BEGIN" . PHP_EOL;
$rbMQs->process_requests('requestProcessor');
echo "RabbitMQServer END" . PHP_EOL;
exit();
?>