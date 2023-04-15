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
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" id="list-item-form">
                <div class="card">
                    <div class="card-header dark-bg" style="color: white; background-color:blueviolet;">
                        <h4>List your Item!</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars_decode($title) ?></h5>
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="<?php echo stripslashes(htmlspecialchars_decode($img)) ?>" class="card-img-top img-fluid" style="max-width: 300px;" alt="...">
                        </div>
                        <div class="mb-3">
                            <label for="genre" class="form-label">
                                <h5>Genres:</h5>
                            </label>
                            <input type="text" value="<?php echo htmlspecialchars_decode($genre) ?>" readonly class="form-control" name="genre" id="genre">
                        </div>
                        <div class="mb-3">
                            <label for="format" class="form-label">
                                <h5>Format:</h5>
                            </label>
                            <input type="text" readonly value="<?php echo htmlspecialchars($format) ?>" name="format" class="form-control" id="format">
                        </div>
                        <div class="mb-3">
                            <label for="condition-select" class="form-label">
                                <h5>Condition:</h5>
                            </label>
                            <select class="form-select" name="condition" id="condition-select" required>
                                <option disabled selected>Condition</option>
                                <?php foreach ($conditons as $c) : ?>
                                    <option value="<?php echo $c ?>"><?php echo $c ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">
                                <h5>Description:</h5>
                            </label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">
                                <h5>Price:</h5>
                            </label>
                            <input type="number" class="form-control" name="price" id="price" step="0.01" min="5" required>
                        </div>
                        <div class="mb-3">
                            <label for="list" class="form-label">
                                <h4>Enter AudioNook's Marketplace!</h4>
                            </label>
                            <input type="submit" name="list" class="btn btn-primary" value="List">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>