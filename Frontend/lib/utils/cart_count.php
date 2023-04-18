<?php
require_once(__DIR__ . "/../functions.php");
$user_id = get_user_id();
$cart_count = 0;
$getCart = new DBRequests();
$response = $getCart->doCart($user_id);
if ($response['code'] == 200) {
    $cart_count = count($response['cart']);
}
if($cart_count > 0){
    json_encode(['count' => $cart_count]);
}

