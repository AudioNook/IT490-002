<?php
require(__DIR__ . "/../partials/nav.php");
$user_id = get_user_id();
$cart = get_cart($user_id,$rbMQCOL);

if(isset($_POST['action'])){
    $action = $_POST['action'];
    $cart_id = $_POST['cart_id'];
    update_cart($action,$user_id,null,null,$rbMQC);
}

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
                    <form method="POST">
                        <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($c["id"]); ?>" />
                        <input type="hidden" name="action" value="update" />
                        <input type="submit" class="btn btn-primary" value="Update Quantity" />
                    </form>
                </td>
                <?php $total += (int)$c["subtotal"]; ?>
                <td>$<?php echo htmlspecialchars($c["subtotal"]); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="cart_id" value="<?php (int)$c["id"]; ?>" />
                        <input type="hidden" name="action" value="delete" />
                        <input type="submit" class="btn btn-danger" value="X" />
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
                <a href="<?php echo get_url('checkout.php');?>" class="btn btn-success">Checkout</a>
            </td>
            <td>
                <form method="POST">
                    <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($c["id"]); ?>" />
                    <input type="hidden" name="action" value="clear" />
                    <input type="submit" class="btn btn-danger" value="Clear Cart X" />
                </form>
            </td>
            <?php endif; ?>
        </tr>
        </tbody>
    </table>
</div>