<?php
require(__DIR__ . "/../partials/nav.php");
logged_in(true);
$user_id = get_user_id();
$action = strtolower(trim(htmlspecialchars($_POST['action'])));
if (!empty($action)) {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $cartOpt = new DBRequests();
        if (array_key_exists('cart_id', $_POST)) {
            $cart_id = $_POST['cart_id'];
            $response = $cartOpt->doCart($user_id, $product_id, $action, $cart_id);
        } else {
            $response = $cartOpt->doCart($user_id, $product_id, $action);
        }
    }
}
$getCart = new DBRequests();
$response = $getCart->doCart($user_id);
if ($response['code'] == 200) {
    $cart = $response['cart'];
} else {
    $cart = [];
    //redirect(get_url("marketplace.php"));
}
//var_dump($cart);
?>
<div class="container-fluid">
    <h1>Cart</h1>
    <table class="table table-striped">
        <?php $total = 0; ?>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $c) : ?>
                <tr>
                    <td><a class="link-success"><?php echo htmlspecialchars($c["name"]); ?></a></td>
                    <td>$<?php echo htmlspecialchars($c["unit_price"]); ?></td>
                    <td>
                        <form method="POST" class="d-inline-flex">
                            <input type="hidden" name="cart_id" value="<?php echo (int)$c["id"]; ?>" />
                            <input type="hidden" name="action" value="update" />
                            <input type="number" readonly class="form-control small-input" id="disabledTextInput" name="desired_quantity" value="<?php echo 1; ?>" min="0" max="<?php echo 1; ?>" />
                            <input type="submit" class="btn btn-primary btn-sm" value="Update" disabled />
                        </form>

                    </td>
                    <?php $total += (int)$c["subtotal"]; ?>
                    <td>$<?php echo htmlspecialchars($c["subtotal"]); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($c["id"]); ?>" />
                            <input type="hidden" name="action" value="delete" />
                            <input type="submit" name="submit" class="btn btn-danger" value="X" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($cart) == 0) : ?>
                <tr>
                    <td colspan="100%">No products in cart</td>
                </tr>
            <?php endif; ?>
            <tr>
                <th colspan="3">Total: $<?php echo $total; ?> </th>
                <?php if (count($cart) !== 0) : ?>
                    <td>
                        <a href="<?php echo get_url('checkout.php'); ?>" class="btn btn-success">Checkout</a>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($c["id"]); ?>" />
                            <input type="hidden" name="action" value="clear" />
                            <input type="submit" class="btn btn-danger" value="Clear" />
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
</div