<?php
require_once(__DIR__ . "/../functions.php");
function handle_review($request)
{
    $table = '';
    switch ($request['type']) {
        case "reviews":
            $table = 'Reviews';
            $reviews = executeQuery("SELECT product_id, comment, product_name, rating, created FROM $table");
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
        'message' => 'An error occurred while processing the request',
    ];
}