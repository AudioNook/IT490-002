<?php
require_once(__DIR__ . "/../functions.php");
function handle_review($request)
{
    $table = '';
    switch ($request['type']) {
        case "reviews":
            $table = 'Reviews';
            $reviews = executeQuery("SELECT product_id, comment, created FROM $table");
            if ($reviews !== false) {
                return [
                    'type' => 'reviews',
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Returning Reviews',
                    'reviews' => $reviews
                ];
            }
            break;
            /////////////////////////////////////////////////
            case 'new_review':
                $product_id = $request['product_id'];
                $comment = htmlspecialchars($request['comment']);

                $query = "INSERT INTO Reviews(product_id, comment) 
                VALUES(:product_id,:comment)";
                $params = [
                    ':product_id' => $product_id,
                    ':comment' => $comment
                ];
                if (executeQuery($query, $params) !== false) {
                    return [
                        'type' => 'new_review',
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Created a new review'
                    ];
                }
                break;
            ////////////////////////////////////////////////
        default:
            return [
                'type' => 'review',
                'code' => 401,
                'status' => 'error',
                'message' => 'Unexpected Error'
            ];
    }
    return [
        'type' => $request['type'],
        'code' => 500,
        'status' => 'error',
        'message' => 'No reviews! Make one.',
    ];
}