<?php

namespace Database;

require_once(__DIR__ . "/db.php");

use Database\db;
use PDOException;
use PDO;

/**
 * Class Cart: Handles all cart related database queries
 * @package Database
 */
class Cart extends db
{

    public function __construct()
    {
        parent::__construct();
    }
    //cart actions
    public function cart($request)
    {
        if (array_key_exists('action', $request)) {
            $action = $request['action'];
            switch ($action) {
                case "add": // add item to cart
                    $query = "INSERT INTO Cart (product_id, unit_price, user_id)
                    VALUES (:iid, (SELECT price FROM marketplace_items where id = :iid), :uid)";
                    $stmt = $this->prepare($query);
                    $stmt->bindValue(":iid", $request['product_id'], PDO::PARAM_INT);
                    $stmt->bindValue(":uid", $request['user_id'], PDO::PARAM_INT);
                    try {
                        $stmt->execute();
                        return [
                            'type' => 'req_cart',
                            'code' => 200,
                            'status' => 'success',
                            'message' => 'Sucessfully added item to cart',
                        ];
                    } catch (PDOException $e) {
                        error_log(var_export($e, true));
                        return [
                            'type' => 'req_cart',
                            'code' => 401,
                            'status' => 'error',
                            'message' => 'Error adding item to cart',
                        ];
                    }
                    break;
                case "update": // update item in cart
                    $query = "UPDATE Cart set desired_quantity = :dq WHERE id = :cid AND user_id = :uid";
                    $stmt = $this->prepare($query);
                    //desired quantity is the new quantity
                    $stmt->bindValue(":dq", $request['desired_quantity'], PDO::PARAM_INT);
                    //cart id specifies a specific cart
                    $stmt->bindValue(":cid", $request['cart_id'], PDO::PARAM_INT);
                    //user id ensures we can only edit our cart
                    $stmt->bindValue(":uid", $request['user_id'], PDO::PARAM_INT);
                    try {
                        $stmt->execute();
                        return [
                            'type' => 'req_cart',
                            'code' => 200,
                            'status' => 'success',
                            'message' => 'Sucessfully updated item in cart',
                        ];
                    } catch (PDOException $e) {
                        if ($request['desired_quantity'] < 0) {
                            error_log(var_export($e, true));
                            return [
                                'type' => 'req_cart',
                                'code' => 401,
                                'status' => 'error',
                                'message' => 'Error updating item in cart: Desired quantity cannot be less than 0',
                            ];
                        }
                        if ($request['desired_quantity'] == 0) {
                            $query = "DELETE FROM Cart WHERE id = :cid AND user_id = :uid";
                            $stmt = $this->prepare($query);
                            $stmt->bindValue(":cid", $request["cart_id"], PDO::PARAM_INT);
                            $stmt->bindValue(":uid", $request['user_id'], PDO::PARAM_INT);
                            try {
                                $stmt->execute();
                                return [
                                    'type' => 'req_cart',
                                    'code' => 200,
                                    'status' => 'Success',
                                    'message' => 'Sucessfully deleted item from cart',
                                ];
                            } catch (PDOException $e) {
                                error_log(var_export($e, true));
                                return [
                                    'type' => 'req_cart',
                                    'code' => 401,
                                    'status' => 'Error',
                                    'message' => 'Error deleting item in cart',
                                ];
                            }
                        }
                        return [
                            'type' => 'req_cart',
                            'code' => 401,
                            'status' => 'Error',
                            'message' => 'Error updating item in cart',
                        ];
                    }
                    break;
                case "delete":
                    $query = "DELETE FROM Cart WHERE id = :cid AND user_id = :uid";
                    $stmt = $this->prepare($query);
                    $stmt->bindValue(":cid", $request["cart_id"], PDO::PARAM_INT);
                    $stmt->bindValue(":uid", $request['user_id'], PDO::PARAM_INT);
                    try {
                        $stmt->execute();
                        return [
                            'type' => 'req_cart',
                            'code' => 200,
                            'status' => 'Success',
                            'message' => 'Sucessfully deleted item from cart',
                        ];
                    } catch (PDOException $e) {
                        error_log(var_export($e, true));
                        return [
                            'type' => 'req_cart',
                            'code' => 401,
                            'status' => 'Error',
                            'message' => 'Error deleting item in cart',
                        ];
                    }
                    break;
                case "clear":
                    //clear cart contents
                    $query = "DELETE FROM Cart";
                    $stmt = $this->prepare($query);
                    try {
                        $stmt->execute();
                        return [
                            'type' => 'req_cart',
                            'code' => 200,
                            'status' => 'Success',
                            'message' => 'Sucessfully cleared items in cart',
                        ];
                    } catch (PDOException $e) {
                        error_log(var_export($e, true));
                        return [
                            'type' => 'req_cart',
                            'code' => 401,
                            'status' => 'Error',
                            'message' => 'Error clearing items in cart',
                        ];
                    }
                    break;
            }
        }
        $query = "SELECT 
                    cart.id, 
                    cart.product_id, 
                    marketplace_items.id AS marketplace_item_id,
                    collection_items.title AS name,
                    cart.unit_price, 
                    (cart.unit_price) as subtotal
                    FROM 
                    Cart as cart
                    JOIN 
                    Marketplace_Items as marketplace_items ON cart.product_id = marketplace_items.id
                    JOIN 
                    Collection_Items as collection_items ON marketplace_items.user_collected_item_id = collection_items.id
                    WHERE 
                        cart.user_id = :uid";
        $stmt = $this->prepare($query);
        $cart = [];
        try {
            $stmt->execute([":uid" => (int)$request['user_id']]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($results) {
                $cart = $results;
                return [
                    'type' => 'req_cart',
                    'code' => 200,
                    'status' => 'Success',
                    'message' => 'Created cart',
                    'cart' => $cart
                ];
            } else {
                return [
                    'type' => 'req_cart',
                    'code' => 200,
                    'status' => 'Success',
                    'message' => 'Cart is empty',
                    'cart' => []
                ];
            }
        } catch (PDOException $e) {
            error_log(var_export($e, true));
            return [
                'type' => 'req_cart',
                'code' => 401,
                'status' => 'Error',
                'message' => 'Error Creating cart: ' . $e->getMessage()
            ];
        }
    }
}
