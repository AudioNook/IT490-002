<?php require_once(__DIR__ . "/../partials/nav.php");
$item = [];
$conditons = ['Poor', 'Fair', 'Good', 'Good Plus', 'Very Good', 'Very Good Plus', 'Near Mint', 'Mint'];
if($isset($_POST['id'])){
    $uid = $_POST['id'];
    $cid = get_user_id();
    $item = get_item($uid,$cid,$rbMQCOL);
}

?>
<br>
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 50vh;">

    <div class="card" style="width: 50%;">
        <div class="card-header dark-bg" style="color: white; background-color:blueviolet;">
            <h4>List your Item!</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">List Product</h5>
            <img src="https://tinyurl.com/5n7fs4w8"class="card-img-top img-fluid d-flex" style="max-width: 200px;" alt="...">
            <div class="mb-3 row">
                <label for="genre" class="col-sm-2 col-form-label">
                    <h5>Genres:</h5>
                </label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="genre">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="format" class="col-sm-2 col-form-label">
                    <h5>Format:</h5>
                </label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="format">
                </div>
            </div>
            <div class="dropdown">
                <select style="background-color:darkgrey;" class="btn dropdown-toggle" type="button" name="condition" id="condition-select" data-bs-toggle="dropdown" aria-expanded="false">
                    <option class="dropdown-menu" aria-labelledby="Condition-select" disabled selected>Condition</option>
                    <?php foreach ($conditons as $c) : ?>
                        <option class="dropdown-item"><?php echo $c ?></option>
                    <?php endforeach; ?>
                </select>
                </ul>
            </div>
            <div class="mb-3 row">
                <label for="exampleFormControlTextarea1" class="form-label">
                    <h5>Description:</h5>
                </label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
            <div class="mb-3 row">
                <label for="format" class="col-sm-2 col-form-label">
                    <h5>Price:</h5>
                </label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="format">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="format" class="col col-form-label">
                    <p>AudioNook's Marketplace</p>
                </label>
                <div class="col">
                    <button type="button" class="btn btn-primary">List</button>
                </div>
            </div>

        </div>
    </div>
</div>