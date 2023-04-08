<?php

namespace Database;

require_once(__DIR__ . "/db.php");

use Database\db;
/**
 * Classwa Products and Reviews: Database class for Products and Reviews
 * @package Database
 */

///////////////////////////////
//Products Class
///////////////////////////////

class Products extends db
{
    /**
     * Forums constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Get all products in Products table
     * @return array
     */
    public function get_products()
    {
        $table = 'Products';
        $products = $this->exec_query("SELECT id, name, category, stock, cost, image FROM $table");
        if ($products) {
            return $products;
        }
    }
}

///////////////////////////////
//Reviews Class
///////////////////////////////

class Reviews extends db
{
    /**
     * Forums constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Get all reviews in Review Table
     * @return array
     */
    public function get_reviews()
    {
        $table = 'Reviews';
        $reviews = $this->exec_query("SELECT id, product_id, comment, created FROM $table");
        if ($reviews) {
            return $reviews;
        }
    }

    /**
     * Get all reviews for a specific user
     * @param $product_id
     * @return array
     */
    public function get_user_reviews($product_id)
    {
        $reviews = $this->exec_query("SELECT Reviews.id, Reviews.product_id, Reviews.comment, Reviews.created FROM Reviews INNER JOIN Users ON Reviews.user_id = Users.id WHERE Reviews.product_id = :p_id", [":p_id" => $product_id]);
        if ($reviews) {
            return $reviews;
        }
    }
    /**
     * Get a single review
     * @param $id
     * @return array
     */
    public function create_review($id, $user_id, $product_id, $comment, $created)
    {
        $query = "INSERT INTO Reviews (id, user_id, product_id, comment, created) VALUES(:id, :user_id. :product_id, :comment, :created)";
        $params = [
            ':id' => $id,
            ':user_id' => $user_id,
            ':product_id' => $product_id,
            ':comment' => $comment,
            ':created' => $created

        ];
        if ($this->exec_query($query, $params) !== false) {
            return true;
        }
    }
}


