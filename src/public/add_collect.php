<?php
require_once(__DIR__ . "/../lib/functions.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"] ?? null;
    $items = $_POST["items"] ?? [];

    if ($user_id && count($items) > 0) {
        $add_collect = array();
        $add_collect['type'] = "add_collect";
        $add_collect['user_id'] = $user_id;
        $add_collect['items'] = $items;
        global $rbMQc;

        $response = json_decode($rbMQc->send_request($add_collect), true);
        switch ($response['code']) {
            case 200:
                $response['success'] = true;
                echo json_encode($response['message']);
                break;
            case 401:
                $response['success'] = false;
                $response['message'] = 'Unauthorized: ' . $response['message'];
                error_log($response['message']);
                break;
            default:
                $response['success'] = false;
                $response['message'] = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
                error_log($response['message']);
                break;
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid request';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
exit(0);
    /*
    $items = array();
    $release_ids = $_POST['release_id'];
    $titles = $_POST['title'];
    $cover_images = $_POST['cover_image'];
    $formats = $_POST['format'];
    $user_id = $_POST['user_id'];
    error_log($user_id);
    $add_collect = array();
    $add_collect['type'] = 'add_collect';
    $add_collect['message'] = "Sending add to collection request";*/

   /* switch ($response['code']) {
        case 200:
            return $response;
        case 401:
            $error_msg = 'Unauthorized: ' . $response['message'];
            error_log($error_msg);
            throw new Exception($error_msg);
        default:
            $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
            error_log($error_msg);
            throw new Exception($error_msg);
    }*/
