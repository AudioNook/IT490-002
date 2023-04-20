<?php
require_once(__DIR__ . "/../lib/functions.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"] ?? null;
    $items = $_POST["items"] ?? [];

    if ($user_id && count($items) > 0){
        $addCollect = new DBRequests();
        $response = $addCollect->addToCollect($user_id, $items);
        }
        
}
    echo json_encode($response);
    exit(0);