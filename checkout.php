<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    include 'admin/blocked_user.php'; 
    // include 'components/payment_alert.php';
} else {
    $user_id = '';
    header('location:user_login.php');
}


// $select_cart = $conn->prepare("
//     SELECT cart.quantity, products.id, products.p_name
//     FROM cart
//     INNER JOIN products ON cart.pid = products.id
//     WHERE cart.user_id = ?
// ");
// $select_cart->execute([$user_id]);

// $productNames = array();

// while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
//     $p_name = $fetch_cart['p_name'];
//     // $pid is now included in the result set
//     $pid = $fetch_cart['id'];  

//     echo $pid;

//     // Check if the product name is not already in the array
//     if (!in_array($p_name, $productNames)) {
//         // If you want to display quantity along with p_name
//         $productNames[] = "$p_name";
//     }
// }
// sort($productNames);

// $productNamesString = implode(', ', $productNames);

// echo "Product Names: $productNamesString";

$message = array();
if (isset($_POST['order'])) {

    $invoice_no = $user_id . time();
    $invoice_no = filter_var($invoice_no, FILTER_SANITIZE_STRING);
    $address =  $_POST['flat']; 
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $city= $_POST['city']; 
    $city = filter_var($city, FILTER_SANITIZE_STRING);
    $state= $_POST['state'];
    $state = filter_var($state, FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_products = filter_var($total_products, FILTER_SANITIZE_STRING);
    $total_price = $_POST['total_price'];
    $total_price = filter_var($total_price, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
   
        // Handle cash on delivery
        include 'components/connect.php';
        global  $message;
        $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $check_cart->execute([$user_id]);
      
    
        if ($check_cart->rowCount() > 0) {           
            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, invoice_no, total_products, total_price) VALUES(?,?,?,?)");
            $insert_order->execute([$user_id, $invoice_no,$total_products, $total_price]);
            $last_inserted_id = $conn->lastInsertId();
            $check_cart_array = $check_cart->fetchAll(PDO::FETCH_ASSOC);

            foreach ($check_cart_array as $cart_item) {
                $pid = $cart_item['pid'];
                $qty = $cart_item['quantity'];


                $insert_order_items = $conn->prepare("INSERT INTO `orders_items`(user_id,oid, pid, order_qty) VALUES(?,?,?,?)");
                $insert_order_items->execute([$user_id,$last_inserted_id, $pid, $qty]);
            }
            $insert_user = $conn->prepare("UPDATE `users` SET state = ?, city = ?, address = ? WHERE id = ?");
            $insert_user->execute([$state, $city, $address, $user_id]);
                    
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);
    
            $message[] = 'Order placed successfully!';
            echo '<script>window.location.href = "payment.php?checkout=' . $last_inserted_id . '";</script>';
           


        } else {
            $message[] = 'Your cart is empty';
            header('location:cart.php');
        }
   
         } 



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>


    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="checkout-orders">

        <form action="" method="POST" id="checkoutForm">

            <h3>Your orders</h3>

            <div class="display-orders">
                <?php
                    $grand_total = 0;
                    $cart_items = array(); // Initialize an empty array

                    $select_cart = $conn->prepare("SELECT products.id AS pid, products.p_name, products.price, cart.quantity FROM `cart` INNER JOIN `products` ON cart.pid = products.id WHERE cart.user_id = ?");
                    $select_cart->execute([$user_id]);

                    if ($select_cart->rowCount() > 0) {
                        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                            $cart_items[] = $fetch_cart['p_name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ')';
                            $total_products = implode(', ', $cart_items);
                            $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);

                        ?>
                <p>
                    <?= $fetch_cart['p_name']; ?>
                    <span>(<?= 'Rs ' . $fetch_cart['price'] . ' * ' . $fetch_cart['quantity']; ?>)</span>

                </p>
                <?php
                        }
                    } else {
                        echo '<p class="empty">Your cart is empty!</p>';
                    }
                            $user_name = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                            $user_name->execute([$user_id]);
                            if ($user_name->rowCount() > 0) {
                                while ($fetch_userDetails = $user_name->fetch(PDO::FETCH_ASSOC)) {
                                    $userName = $fetch_userDetails['name'];
                                    $userEmail = $fetch_userDetails['email'];
                                    $userPhone = $fetch_userDetails['phone'];
                                    $userState = $fetch_userDetails['state'];
                                    $userCity = $fetch_userDetails['city'];
                                    $userAddress = $fetch_userDetails['address'];
                                    
                                }
                            }
                        ?>
                <input type="hidden" name="price" value="<?=$fetch_cart['price'] ?>">
                <input type="hidden" name="total_products" value="<?= $total_products; ?>">
                <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
                <div class="grand-total">Grand Total : Rs<span id="grand_total"></span><?= $grand_total; ?></span>/-
                </div>
                <div class="grand-total-words">In Words : <span id="word_payment"></span>
                </div>
            </div>

            <h3>Place your orders</h3>

            <div class="flex">
                <div class="inputBox">
                    <input type="hidden" name="id" value="<?= $order_id?>  ">
                    <span>Name :</span>
                    <input type="text" value="<?= htmlspecialchars($userName) ?>" name="name" readonly
                        placeholder="Name" class="box" maxlength="20" id="usersName" required>
                </div>
                <div class="inputBox">
                    <span>Phone no. :</span>
                    <input type="number" value="<?= htmlspecialchars($userPhone) ?>" name="number" readonly
                        placeholder="Number" class="box" min="0" id="phone" required>
                </div>
                <div class="inputBox">
                    <span>Email :</span>
                    <input type="email" value="<?= htmlspecialchars($userEmail) ?>" name="email" placeholder="Email"
                        readonly class="box" maxlength="50" id="email" required>
                </div>

                <div class="inputBox">
                    <span>State:</span>
                    <select name="state" class="box" id="statename" required>
                        <option value="">Select State</option>
                        <option value="Koshi State"
                            <?= ($fetch_profile["state"] == 'Koshi State') ? 'selected' : ''; ?>>Koshi State
                        </option>
                        <option value="Madesh State"
                            <?= ($fetch_profile["state"] == 'Madesh State') ? 'selected' : ''; ?>>Madesh State
                        </option>
                        <option value="Bagmati State"
                            <?= ($fetch_profile["state"] == 'Bagmati State') ? 'selected' : ''; ?>>Bagmati State
                        </option>
                        <option value="Gandaki State"
                            <?= ($fetch_profile["state"] == 'Gandaki State') ? 'selected' : ''; ?>>Gandaki State
                        </option>
                        <option value="Lumbini State"
                            <?= ($fetch_profile["state"] == 'Lumbini State') ? 'selected' : ''; ?>>Lumbini State
                        </option>
                        <option value="Karnali State"
                            <?= ($fetch_profile["state"] == 'Karnali State') ? 'selected' : ''; ?>>Karnali State
                        </option>
                        <option value="Sudurpaschim State"
                            <?= ($fetch_profile["state"] == 'Sudurpaschim State') ? 'selected' : ''; ?>>
                            Sudurpaschim State</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>City :</span>
                    <input type="text" value="<?= htmlspecialchars($userCity) ?>" name="city" id="city"
                        placeholder="e.g. kathmandu" class="box" maxlength="50" required>
                </div>
                <div class="inputBox">
                    <span>Address :</span>
                    <input type="text" value="<?= htmlspecialchars($userAddress) ?>" name="flat" id="address"
                        placeholder="e.g.Tole" class="box" maxlength="500" required>
                </div>
                <div id="submit-button">
                    <input type="submit" name="order" class="btn placeOrder" value="Place order" id="checkoutForm">
                </div>
            </div>
        </form>


        <script>
        function convertNumberToWords(number) {
            const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            const teens = ['', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen',
                'Eighteen',
                'Nineteen'
            ];
            const tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

            function convertGroup(number) {
                if (number === 0) return '';

                let output = '';

                if (number >= 100) {
                    output += ones[Math.floor(number / 100)] + ' Hundred ';
                    number %= 100;
                }

                if (number >= 20) {
                    output += tens[Math.floor(number / 10)] + ' ';
                    number %= 10;
                }

                if (number > 0) {
                    if (number < 10) {
                        output += ones[number] + ' ';
                    } else {
                        output += teens[number - 10] + ' ';
                    }
                }

                return output;
            }

            if (number === 0) {
                return 'Zero';
            }

            let result = '';
            let billion = Math.floor(number / 1000000000);
            let million = Math.floor((number % 1000000000) / 1000000);
            let thousand = Math.floor((number % 1000000) / 1000);
            let remainder = number % 1000;

            if (billion > 0) {
                result += convertGroup(billion) + 'Billion ';
            }

            if (million > 0) {
                result += convertGroup(million) + 'Million ';
            }

            if (thousand > 0) {
                result += convertGroup(thousand) + 'Thousand ';
            }

            result += convertGroup(remainder);

            return result.trim();
        }

        // Example usage:
        const numericValue = <?= $grand_total; ?>;
        const wordRepresentation = convertNumberToWords(numericValue);
        document.getElementById('word_payment').innerHTML = (
            wordRepresentation +
            " only ");
        </script>
        </div>

    </section>



    <?php include 'components/footer.php'; ?>


    <script src="js/script.js"></script>



</body>

</html>