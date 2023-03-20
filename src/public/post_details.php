<?php
require(__DIR__ . "/../partials/nav.php");

$post_id = $_GET["id"];

if (!isset($_GET["id"]) || is_null($post_id) > 0 || $post_id < 0) {
    error_log("Empty discussion!");
    redirect(get_url("forums.php"));
}
// Fetch and display discussion
$msg = "Sending discussion topic request";

$disucison_req = array();
$disucison_req['type'] = 'discussion';
$disucison_req['post_id'] = $post_id;
$initial_post;
// $replies to said post
$response = json_decode($rbMQc->send_request($disucison_req), true);
if ($response['type'] == 'discussion') {
    switch ($response['code']) {
        case 200:
            $initial_post = $response['initial_post'];
            error_log("succesfully grabbed posts");
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
if (isset($_POST['reply_msg']) && isset($_GET["id"])) {
    $bad_msg = "You must be logged in do this!!";
    if (!logged_in()) {
        echo '<script language="javascript">';
        echo 'alert("' . $bad_msg . '")';
        echo '</script>';
        redirect(get_url("login.php"));
    }

    $reply_msg = $_POST['reply_msg'];
    $hasError = false;
    switch (true) {
        case empty($reply_msg):
            $hasError = true;
            $errorMsg = "Password cannot be empty.";
            break;
    }
    if (!$hasError) {

        $user_id = get_user_id();

        $reply_req = array();
        $reply_req['type'] = 'reply';
        $reply_req['reply_msg'] = $reply_msg;
        $reply_req['user_id'] = $user_id;
        $reply_req['post_id'] = $post_id;

        $created_post = json_decode($rbMQc->send_request($reply_req), true);
        if ($reply_req['type'] == 'reply') {
            switch ($created_post['code']) {
                case 200:
                    error_log("Sucessfully uploaded post");
                    echo '<script language="javascript">';
                    echo 'alert("' . $response['message'] . '")';
                    echo '</script>';
                    //redirect(get_url("discussion.php?=".$created_post['id']));
                    break;
                case 401:
                    echo '<script language="javascript">';
                    echo 'alert("' . $response['message'] . '")';
                    echo '</script>';
                    break;
                default:
                    error_log($response['message']);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion</title>
</head>

<body>
    <div class="container">
        <h1>Discussion</h1>

        <?php if (!empty($initial_post)): ?>
            <div class="card mt-3">
                <h5 class="card-header"><?php echo $initial_post[0]['post_title']; ?></h5>
                <div class="card-body">
                    <p class="card-text"><?php echo $initial_post[0]['content']; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <h3 class="mt-5">Replies</h3>

        <?php if (!empty($response['replies'])): ?>
            <?php foreach ($response['replies'] as $reply): ?>
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="card-text"><?php echo $reply['content']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No replies yet. Be the first one to reply!</p>
        <?php endif; ?>

        <?php if (logged_in()): ?>
            <form action="" method="POST" class="mt-5">
                <div class="form-group">
                    <label for="reply_msg">Post a Reply:</label>
                    <textarea class="form-control" id="reply_msg" name="reply_msg" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Reply</button>
            </form>
        <?php else: ?>
            <p class="mt-5">Please <a href="login.php">login</a> to post a reply.</p>
        <?php endif; ?>

    </div>
</body>

</html>