<?php
require(__DIR__ . "/../partials/nav.php");

$topic_id= $_GET["id"];


if (!isset($_GET["id"])|| is_null($topic_id) > 0 || $topic_id < 0) {
    error_log("no posts!");
    redirect(get_url("forums.php"));
}


$msg = "Sending discussion topic request";

$posts_req = array();
$posts_req['type'] = 'posts';
$posts_req['topic_id'] = $topic_id;
$posts;
$response = json_decode($rbMQCOL->send_request($posts_req), true);
if ($response['type'] == 'posts') {
    switch ($response['code']) {
        case 200:
            $posts = $response['posts'];
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
if (isset($_POST['title']) && isset($_POST['content'])) {
    $bad_msg = "You must be logged in to this!!";
    if (!logged_in()) {
        echo '<script language="javascript">';
        echo 'alert("' . $bad_msg . '")';
        echo '</script>';
        redirect(get_url("login.php"));
    }

    $title = $_POST['title'];
    $content = $_POST['content'];
    $hasError = false;
    switch (true) {
        case empty($title):
            $hasError = true;
            $errorMsg = "Username cannot be empty.";
            break;
            // add check for valid username
        case empty($content):
            $hasError = true;
            $errorMsg = "Password cannot be empty.";
            break;
            // add case for checking valid password
    }
    if (!$hasError && isset($_GET['id'])) {

        $user_id = get_user_id();

        $create_post_req = array();
        $create_post_req['type'] = 'create_post';
        $create_post_req['title'] = $title;
        $create_post_req['reply_msg'] = $content;
        $create_post_req['user_id'] = $user_id;
        $create_post_req['topic_id'] = $topic_id;

        $created_post = json_decode($rbMQc->send_request($create_post_req), true);
        error_log("REEEEEEEE");
        if ($created_post['type'] == 'create_post') {
            switch ($created_post['code']) {
                case 200:
                    error_log("Sucessfully uploaded post");
                    echo '<script language="javascript">';
                    echo 'alert("' . $created_post['message'] . '")';
                    echo '</script>';
                    redirect(get_url("posts.php?id=".$topic_id));
                    break;
                case 401:
                    echo '<script language="javascript">';
                    echo 'alert("' . $created_post['message'] . '")';
                    echo '</script>';
                    break;
                default:
                    error_log($created_post['message']);
            }
        }
    }
}

?>
<html>

<head>
    <script>
        //validateJWT();
    </script>
    <title>AudioNook Forums</title>
</head>
<br>
<?php if (!empty($posts)) : ?>
    <div class="container-fluid">

        <?php foreach ($posts as $post) : ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5> <?php echo $post['username']; ?></h5>
                            <div class="text-muted small"><?php echo $post['created']; ?></div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars_decode($post['post_title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars_decode($post['content']); ?></p>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <a href="<?php echo get_url("post_details.php?id=" . $post['id']);?>" class="btn btn-primary"><i class="ion ion-md-create"></i>&nbsp; View</a>
                        </div>
                    </div> 
                </div>
            </div>
        <?php endforeach; ?>


    <?php else : ?>
        <div>
            <div class="card m-3">
                <h5 class="card-header">Unable to Load Topics!</h5>
                <div class="card-body">
                    <p class="card-text">Try again next semester</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>

    <div class="container-fluid">
        <form class="form" method="POST"> <!-- validate onsubmit=retunr()askdmnaks -->
            <!-- Title input -->
            <div class="form-outline mb-4">
                <input type="text" name="title" class="form-control" placeholder="What do you want to discuss?" />
                <label class="form-label" for="title">Post Title</label>
            </div>

            <!-- Message input -->
            <div class="form-outline mb-4">
                <textarea class="form-control" placeholder="Enter content" name="content" rows="4"></textarea>
                <label class="form-label" for="content">Message</label>
            </div>

            <!-- Submit and Clear button -->
            <button type="reset" class="btn btn-secondary btn-block mb-4">Clear All</button>
            <button type="submit" class="btn btn-primary btn-block mb-4">
                Post
            </button>
        </form>
    </div>