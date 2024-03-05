<?php
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in
    header('location:user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
include 'admin/blocked_user.php';
// include 'components/payment_alert.php';


// Handle delete item from cart
if(isset($_POST['delete'])){
    $cart_id = $_POST['cart_id'];
    $update_stock = $conn->prepare("UPDATE cart INNER JOIN products ON cart.pid = products.id SET products.P_quantity = products.P_quantity + cart.quantity WHERE cart.id = ?");
    $update_stock->execute([$cart_id]);
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$cart_id]);
    header('location:cart.php');
    exit;
}

// Handle delete all items from cart
if (isset($_GET['delete_all'])) {
    $update_stock = $conn->prepare("UPDATE cart INNER JOIN products ON cart.pid = products.id SET products.P_quantity = products.P_quantity + cart.quantity WHERE cart.user_id = ?");
    $update_stock->execute([$user_id]);
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
    exit;
}
// Handle update quantity
// Handle update quantity
if (isset($_POST['update_qty'])) {
    $cart_id = filter_var($_POST['cart_id'], FILTER_SANITIZE_NUMBER_INT);
    $qty = filter_var($_POST['qty'], FILTER_SANITIZE_NUMBER_INT);

    // Fetch product details and current stock quantity
    $stock_query = $conn->prepare("SELECT cart.pid, products.P_quantity FROM cart INNER JOIN products ON cart.pid = products.id WHERE cart.id = ?");
    $stock_query->execute([$cart_id]);
    $stock_result = $stock_query->fetch(PDO::FETCH_ASSOC);
    $current_stock = $stock_result['P_quantity'];
    $pid = $stock_result['pid'];

   if (!ctype_digit($qty) || $qty <= 0) {
        $message[] = 'Invalid quantity';
    } else {
        // Calculate the difference in quantity
        $quantity_difference = $qty - $_POST['original_qty'];

        // Check if the new stock quantity after the update is not below zero
        if ($current_stock - $quantity_difference >= 0) {
            // Update the cart quantity
            $update_qty = $conn->prepare("UPDATE `cart` SET quantity = quantity + :qty WHERE id = :cart_id");
            $update_qty->bindParam(':qty', $quantity_difference, PDO::PARAM_INT);
            $update_qty->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);

            if ($update_qty->execute()) {
                // Update the product stock quantity in a single query
                $stock_update = $conn->prepare("UPDATE `products` SET P_quantity = P_quantity - :qty WHERE id = :pid");
                $stock_update->bindParam(':qty', $quantity_difference, PDO::PARAM_INT);
                $stock_update->bindParam(':pid', $pid, PDO::PARAM_INT);
                
                if ($stock_update->execute()) {
                    $message[] = 'Cart quantity updated';
                } else {
                    // Handle update failure (e.g., display an error message)
                    $message[] = 'Failed to update product stock quantity';
                }
            } else {
                // Handle update failure (e.g., display an error message)
                $message[] = 'Failed to update cart quantity';
            }
        } else {
            $message[] = 'Sorry, the requested quantity exceeds the available stock.';
            $message[] = 'Only ' . $current_stock . ' items left in stock';
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
</head>
<style>
#quantityError {
    color: red;
    opacity: 0;
    display: none;
    transition: opacity 0.3s ease-in-out;
}
</style>


<body>

    <?php include 'components/user_header.php'; ?>

    <section class="products shopping-cart">
        <h3 class="heading">Shopping cart</h3>
        <div class="box-container">

            <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("
      SELECT cart.id as cart_id, cart.*, products.*,products.admin_id as admin_id
      FROM cart
      INNER JOIN products ON cart.pid = products.id
      WHERE cart.user_id = ?
  ");
  
  $select_cart->execute([$user_id]);  
  if ($select_cart->rowCount() > 0) {
      while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
         is_numeric( $quantity_difference = $fetch_cart['quantity'] + $fetch_cart['P_quantity']);
          ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="cart_id" value="<?= $fetch_cart['cart_id']; ?>">
                <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                <img src="uploaded_img/<?= $fetch_cart['image_01']; ?>" alt="">
                <div class="name"><?= $fetch_cart['p_name']; ?></div>
                <input type="hidden" name="original_qty" value="<?= $fetch_cart['quantity']; ?>">
                <div class="flex">
                    <div class="price">Rs <?= $fetch_cart['price']; ?>/-</div>
                    <input type="number" name="qty" class="qty" id="quantityInput" min="1"
                        max="<?= $quantity_difference ?>" onkeypress="if(this.value.length == 2) return false;"
                        value="<?= $fetch_cart['quantity']; ?>">
                    <button type="submit" class="fas fa-edit" name="update_qty"></button>
                </div>
                <span id="quantityError" class="redText"></span>

                <div class="sub-total"> Sub total :
                    <span>Rs <?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span>
                </div>
                <input type="submit" value="Delete item" onclick="return confirm('Delete this from cart?');"
                    class="delete-btn" name="delete">
            </form>
            <?php
   $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">Your cart is empty</p>';
   }
   ?>
        </div>
        <div class="cart-total">
            <p>Grand total : <span>Rs <?= $grand_total; ?>/-</span></p>
            <a href="shop.php" class="option-btn">Continue shopping</a>
            <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>"
                onclick="return confirm('Delete all from cart?');">Delete all item</a>
            <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to checkout</a>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var quantityInput = document.getElementById('quantityInput');
        var errorSpan = document.getElementById('quantityError');
        var showError = false; // Flag to track whether to show the error

        quantityInput.addEventListener('input', function() {
            validateQuantity(quantityInput, errorSpan);
        });

        quantityInput.addEventListener('mouseenter', function() {
            validateQuantity(quantityInput, errorSpan);
        });

        quantityInput.addEventListener('mouseleave', function() {
            if (showError) {
                // If showError flag is true, meaning an error was shown, hide it now
                hideErrorWithAnimation(errorSpan);
                showError = false;
            }
        });

        quantityInput.addEventListener('keyup', function() {
            validateQuantity(quantityInput, errorSpan);
        });
    });

    function validateQuantity(input, errorSpan) {
        var maxQuantity = <?= $quantity_difference ?>;
        var enteredQuantity = parseInt(input.value);

        if (enteredQuantity > maxQuantity) {
            showError = true;
            input.setCustomValidity('Not enough stock');
            errorSpan.textContent = 'Not enough stock';
            showErrorWithAnimation(errorSpan);
        } else if (enteredQuantity < 1) {
            showError = true;
            input.setCustomValidity('Quantity cannot be less than 1');
            errorSpan.textContent = 'Quantity cannot be less than 1';
            showErrorWithAnimation(errorSpan);
        } else {
            if (showError) {
                // If showError flag is true, meaning an error was shown, hide it now
                hideErrorWithAnimation(errorSpan);
                showError = false;
            }
            input.setCustomValidity('');
            errorSpan.textContent = '';
        }
    }

    function showErrorWithAnimation(errorSpan) {
        errorSpan.style.display = 'block';
        // Triggering reflow to apply the initial display: block
        void errorSpan.offsetWidth;
        errorSpan.style.opacity = 1;
    }

    function hideErrorWithAnimation(errorSpan) {
        errorSpan.style.opacity = 0;
        setTimeout(function() {
            errorSpan.style.display = 'none';
        }, 300); // 300ms, same as the transition duration
    }
    </script>



    <script src="js/script.js"></script>



    </form>

</body>

</html>