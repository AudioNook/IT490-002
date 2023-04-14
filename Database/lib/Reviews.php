<?php

namespace Database;

require_once(__DIR__ . "/db.php");

use Database\db;
/**
 * Classes Reviews: Database class for Reviews
 * @package Database
 */

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
        if ($reviews !== false && !empty($reviews)){
            return [
                'code'=>200,
                'message'=> 'Sending reviews data',
                'reviews' => $reviews
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to retrieve reviews data',
            ];
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
        if ($reviews !== false && !empty($reviews)){
            return [
                'code'=>200,
                'message'=> 'Sending user reviews data',
                'reviews' => $reviews
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to retrieve user reviews data',
            ];
        }
    }
    /**
     * Get a single review
     * @param $id
     * @return array
     */
    public function create_review($id, $user_id, $product_id, $comment)
    {
        $query = "INSERT INTO Reviews (id, user_id, product_id, comment) VALUES(:id, :user_id, :product_id, :comment)";
        $params = [
            ':id' => $id,
            ':user_id' => $user_id,
            ':product_id' => $product_id,
            ':comment' => $comment

        ];
        
        if ($this->exec_query($query, $params) !== false){
            return [
                'code'=>200,
                'message'=> 'Review created',
                'success' => true
            ];
        }
        else{
            return [
                'code'=>400,
                'message'=> 'Unable to create review',
            ];
        }
    }
}
