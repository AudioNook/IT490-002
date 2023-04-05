<?php require_once(__DIR__ . "/../partials/nav.php");
$item = [];
$conditons = ['Poor', 'Fair', 'Good', 'Good Plus', 'Very Good', 'Very Good Plus', 'Near Mint', 'Mint'];
$collect_id = null;
$user_id = null;
$response = "";
if(isset($_GET['id'])){
    $collect_id = $_GET['id'];
}
if(isset($_GET['uid'])){
    $user_id = $_GET['uid'];
}
$response = get_item($user_id,$collect_id,$rbMQCOL);
if(count($response['item'][0])>0){
    $item = $response['item'][0];
    $img = $item['cover_image'];
    $title = $item['title'];
    $format = $item['format'];
    $genre = $item['genres'];
}
//echo var_dump($item);
if(isset($_POST['list'])){
    $condition = $_POST['condition'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    /*if(list_item($user_id,$collect_id,$condition,$description,$price,$rbMQc)){
        redirect(get_url('marketplace.php'));
    }*/
    $list_item= array();
    $list_item['type'] = 'list_item';
    $list_item['uid'] = $user_id;
    $list_item['cid'] = $collect_id;
    $list_item['condition'] = $condition;
    $list_item['description'] = $description;
    $list_item['price'] = $price;
    $response = json_decode($rbMQc->send_request($list_item), true);
    switch ($response['code']) {
        case 200:
            error_log($response['message']);
            //redirect(get_url('marketplace.php'));
            //return true;
            break;
        case 401:
            $error_msg = 'Unauthorized: ' . $response['message'];
            error_log($error_msg);
            //window.alert($error_msg);
            break;
        default:
            $error_msg = 'Unexpected response code from server: ' . $response['code'] . ' ' . $response['message'];
            error_log($error_msg);
            //alert($error_msg);
            break;

    }

}

?>

<br>
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <form method="POST" id="list-item-form">
    <div class="card" style="width: 50%;">
        <div class="card-header dark-bg" style="color: white; background-color:blueviolet;">
            <h4>List your Item!</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars_decode($title) ?></h5>
            <div class="d-flex justify-content-center align-items-center">
                <img src="<?php echo stripslashes(htmlspecialchars_decode($img))?>"class="card-img-top img-fluid" style="max-width: 300px;" alt="...">
            </div>
            <div class="mb-3 row">
                <label for="genre" class="col-sm-2 col-form-label">
                    <h5>Genres:</h5>
                </label>
                <div class="col-sm-10">
                    <input type="text" value="<?php echo htmlspecialchars_decode($genre) ?>"readonly class="form-control" name="genre" id="genre">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="format" class="col-sm-2 col-form-label">
                    <h5>Format:</h5>
                </label>
                <div class="col-sm-10">
                    <input type="text" readonly value="<?php echo htmlspecialchars($format)?>" name="format" class="form-control" id="format">
                </div>
            </div>
            <div class="dropdown">
                <select style="background-color:darkgrey;" class="btn dropdown-toggle" type="button" name="condition" id="condition-select" data-bs-toggle="dropdown" aria-expanded="false" required>
                    <option class="dropdown-menu" aria-labelledby="Condition-select" disabled selected>Condition</option>
                    <?php foreach ($conditons as $c) : ?>
                        <option value="<?php echo $c ?>" class="dropdown-item"><?php echo $c ?></option>
                    <?php endforeach; ?>
                </select>
                </ul>
            </div>
            <div class="mb-3 row">
                <label for="exampleFormControlTextarea1" class="form-label">
                    <h5>Description:</h5>
                </label>
                <textarea class="form-control" id="exampleFormControlTextarea1" name="description"rows="3" required></textarea>
            </div>
            <div class="mb-3 row">
                <label for="format" class="col-sm-2 col-form-label">
                    <h5>Price:</h5>
                </label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="price" id="format" step="0.01" min="5" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="format" class="col col-form-label">
                    <h4>Enter AudioNook's Marketplace!</h4>
                </label>
                <div class="col">
                    <input type="submit" name="list"class="btn btn-primary" value="List">
                </div>
            </div>

        </div>
    </div>
    </form>
</div>