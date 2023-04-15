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
// TODO Warning: Undefined variable $rbMQCOL in IT490-002/Frontend/public/list_item.php on line 14
$request = new DBRequests();
$response = $request->getItem($user_id,$collect_id);
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
    $listRequest = new DBRequests();
    $listResponse = $listRequest->listItem($user_id,$collect_id, $condition, $description,$price);

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
                    <?php endforeach; 
                    //TODO Fatal error: Uncaught Error: Call to a member function send_request() on null in /Users/luanda/IT490-002/Frontend/lib/user_functions.php:94 Stack trace: #0 /Users/luanda/IT490-002/Frontend/public/list_item.php(13): get_item(NULL, NULL, NULL) #1 {main} thrown in /Users/luanda/IT490-002/Frontend/lib/user_functions.php on line 94
                    ?>
                    
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