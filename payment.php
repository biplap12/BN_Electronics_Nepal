<?php 

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    include 'admin/blocked_user.php'; 
} else {
    $user_id = '';
    header('location:user_login.php');
    die();
}


    $payment_method = filter_input(INPUT_POST, 'payment', FILTER_SANITIZE_STRING);

    if(isset($_GET['order_id'])){
        $order_id = $_GET['order_id'];
        if(isset($_POST['submit'])){
          
        $select_method = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
        $select_method->execute([$order_id]);

        if ($select_method->rowCount() > 0) {
            $fetch_method = $select_method->fetch(PDO::FETCH_ASSOC);

            $total = $fetch_method['total_price'];
            $fetch_payment_method = $fetch_method['method'];

        if ($fetch_payment_method == 'cash-on-delivery' && ($payment_method == 'khalti' || $payment_method == 'esewa')) {
            echo '<script>
                alert("You have already selected cash on delivery");
                window.location.href = "orders.php";
                </script>';
            die();
        } elseif ($payment_method == 'cash-on-delivery') {
            $total += 50;

            $update_method = $conn->prepare("UPDATE `orders` SET `method` = ?, `total_price` = ? WHERE id = ?");
            $update_method->execute([$payment_method, $total, $order_id]);
        }
      
        header('location: success_payment.php?Payment_id=' . $order_id . '');
exit();

}}}

if(isset($_GET['checkout'])){
    $order_id = $_GET['checkout'];
if (isset($_POST['submit'])) {

// Fetch a specific order for the user (you might need an ORDER BY clause to determine which order)
$select_method = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
$select_method->execute([$order_id]);

if ($select_method->rowCount() > 0) {
$fetch_method = $select_method->fetch(PDO::FETCH_ASSOC);
$total = $fetch_method['total_price'];
$fetch_payment_method = $fetch_method['method'];

// Check if the payment method is 'cash-on-delivery' and add the additional fee
if ($fetch_payment_method == 'cash-on-delivery' && ($payment_method == 'khalti' || $payment_method == 'esewa')) {
echo '<script>
alert("You have already selected cash on delivery");
window.location.href = "orders.php";
</script>';
exit();
} elseif ($payment_method == 'cash-on-delivery') {
$total += 50;



$update_method = $conn->prepare("UPDATE `orders` SET `method` = ?, `total_price` = ? WHERE id = ?");
$update_method->execute([$payment_method, $total, $order_id]);
}
// Redirect to the order.php page or wherever needed
header('location: success_payment.php?Payment_id=' . $order_id . '');
exit();
}
}}

$select_orderName = $conn->prepare("SELECT * FROM `orders` WHERE id = ? ");
$select_orderName->execute([$order_id]);
$fetch_orderName = $select_orderName->fetch(PDO::FETCH_ASSOC);
$total = $fetch_orderName['total_price'];
$itemNames = $fetch_orderName['total_products'];
$invoice_no = $fetch_orderName['invoice_no'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="css/payment.css?<?php echo time(); ?>">
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!-- Include SweetAlert CSS -->
    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


</head>

<body>
    <div id="loader" class="loader-overlay" style="display: none;">
        <div class="loader"> </div>
        <div class="loading"> &nbsp; &nbsp; Please Wait...</div>
        <div>&nbsp; &nbsp; &nbsp; &nbsp;You will receive an email bill for your order details.</div>
    </div>
    <div class="card">
        <div class="card-header">
            Payment Options
        </div>
        <div class="card-body">
            <div id="payment-button">
                <img src="images/khalti_logo.png" alt="Khalti Logo">
            </div>

            <!-- <div id="esewa">
                <img src="images/esewa-logo.png" alt="Esewa Logo">
                <form action="https://uat.esewa.com.np/epay/main" method="POST" id="esewaForm">
                    <input value="<?php echo $total;?>" name="tAmt" type="hidden">
                    <input value="<?php echo $total;?>" name="amt" type="hidden">
                    <input value="0" name="txAmt" type="hidden">
                    <input value="0" name="psc" type="hidden">
                    <input value="0" name="pdc" type="hidden">
                    <input value="epay_payment" name="scd" type="hidden">
                    <input value="<?php echo $invoice_no;?>" name="pid" type="hidden">
                    <input value="http://localhost/BN_Electronics_Nepal/esewa.php"
                        type="hidden" name="su">
                    <input value="http://localhost/BN_Electronics_Nepal/paymentfail.php"
                        type="hidden" name="fu">
                    <input type="submit" id="esewa_btn" style="display: none;">
                </form>
            </div> -->

            <form action="" method="post">
                <div id="cod_radio">
                    <img src="images/cash-on-delivery.png" alt="COD Logo">
                    <input type="radio" name="payment" id="cod_radio_input"
                        value="<?= htmlspecialchars('cash-on-delivery') ?>">
                </div>
                <div id="additionalFeeMessage" style="display: none;">
                    An additional fee of <span id="extraCharge"></span> is applicable for Cash on Delivery (COD)
                    transactions.
                </div>
                <div id="Pay_btn">
                    <input type="submit" name="submit" style="display: none;" id="placeOrder_btn" value="Submit">
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var codRadio = document.getElementById('cod_radio_input');
        var codRadioLabel = document.getElementById('cod_radio');
        var additionalFeeMessage = document.getElementById('additionalFeeMessage');
        var placeOrder_btn = document.getElementById('placeOrder_btn');
        var khaltiRadio = document.getElementById('payment-button');
        var esewaRadio = document.getElementById('esewa');
        var extraCharge = 50; // Set the additional fee amount

        placeOrder_btn.addEventListener('click', function() {
            document.getElementById('loader').style.display = 'flex';
            document.getElementsByClassName('card')[0].style.opacity = '0.2';
            document.getElementsByClassName('card')[0].style.pointerEvents = 'none';

        });

        codRadioLabel.addEventListener('click', function() {
            codRadio.checked = true;

            additionalFeeMessage.style.transition = 'opacity 0.5s'; // Smooth transition
            additionalFeeMessage.style.opacity = 1; // Make sure it's visible
            additionalFeeMessage.style.display = 'block';
            placeOrder_btn.style.display = 'block';
            document.getElementById('extraCharge').innerText = "Rs" + " " + extraCharge;
        });

        codRadio.addEventListener('change', function() {
            if (codRadio.checked) {
                additionalFeeMessage.style.transition = 'opacity 0.5s'; // Smooth transition
                additionalFeeMessage.style.opacity = 1; // Make sure it's visible
                additionalFeeMessage.style.display = 'block';
                placeOrder_btn.style.display = 'block';
                document.getElementById('extraCharge').innerText = "Rs" + " " + extraCharge;
            } else {
                additionalFeeMessage.style.transition = 'opacity 0.5s'; // Smooth transition
                additionalFeeMessage.style.opacity = 0;
                setTimeout(function() {
                    additionalFeeMessage.style.display = 'none';
                }, 500); // Wait for the transition to complete
                placeOrder_btn.style.display = 'none';
            }
        });


        khaltiRadio.addEventListener('click', function() {
            placeOrder_btn.style.display = 'none';
            additionalFeeMessage.style.display = 'none';
            codRadio.checked = false;
        });



        document.getElementById("esewa").addEventListener("click", function() {
            document.getElementById("esewa_btn").click();
        });

    });
    </script>


    <?php
   

preg_match_all('/([a-zA-Z\s]+) \(\d+ x \d+\)/', $itemNames, $matches);

// $matches[1] contains the extracted item names
$itemNamesArray = $matches[1];

// Display the item names
foreach ($itemNamesArray as $itemName) {
    $formattedItemNames = implode(', ', $itemNamesArray);
    // echo $formattedItemNames;
}
?>


    <script type="text/javascript">
    let total = <?php echo json_encode($total); ?>;
    let productName = <?php echo json_encode($formattedItemNames); ?>;
    let productIdentity = <?php echo json_encode($order_id); ?>;
    var config = {
        "publicKey": "test_public_key_a975b7c5a6a74153b506ecaa539db7fa",
        "productIdentity": productIdentity,
        "productName": productName,
        "productUrl": "http://localhost/BN_Electronics_Nepal/home.php",
        "return_url": "http://localhost/BN_Electronics_Nepal/success_payment.php?Payment_id=" +
            productIdentity,
        "paymentPreference": ["KHALTI"],
        "eventHandler": {
            onSuccess(payload) {
                Swal.fire({
                    title: 'Processing Payment...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                        $(".card").css({
                            opacity: "0.2",
                            pointerEvents: "none"
                        });
                    }
                });
                // console.log(payload);
                var token = payload.token;
                var amount = payload.amount;
                var productName = payload.product_name;
                var id = productIdentity;
                var idx = payload.idx;
                $(document).ready(function() {
                    $.ajax({
                        url: 'khalti.php',
                        type: 'POST',
                        data: {
                            id: id,
                            idx: idx,
                            token: token,
                            amount: amount,
                            productName: productName,
                            method: 'Khalti',
                        },
                        dataType: 'json',
                        success: function(response) {
                            // console.log(response);
                            if (response.status === 'success') {
                                // Success popup
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Payment Successful😊',
                                    text: response.message,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    allowEnterKey: false,
                                    onBeforeOpen: () => {
                                        Swal.showLoading();

                                    }
                                });
                                // Redirect after the popup is closed
                                window.location.href =
                                    "success_payment.php?Payment_id=" +
                                    productIdentity;

                            } else {
                                // Error popup
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Payment Failed😭',
                                    text: 'Payment update failed: ' + response
                                        .message,
                                }).then((result) => {
                                    // Redirect after the popup is closed
                                    window.location.href =
                                        "paymentfail.php";
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // console.log(error);
                            // alert("An error occurred while processing the payment.");
                            Swal.fire({
                                icon: 'error',
                                title: 'Payment Failed😭',
                                text: 'An error occurred while processing the payment.',
                                timer: 3000, // Automatically close after 2 seconds
                            }).then((result) => {
                                // Redirect after the popup is closed
                                window.location.href =
                                    "paymentfail.php";
                            });
                        }
                    })
                });


            },
            onError(error) {
                // console.log(error);
                // alert("Payment Failed");
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed😭',
                    text: 'Payment Failed',
                    timer: 3000, // Automatically close after 2 seconds
                }).then((result) => {
                    // Redirect after the popup is closed
                    window.location.href =
                        "paymentfail.php";
                });
            },
            onClose() {
                // console.log('Widget is closing');
                Swal.fire({
                    icon: 'info',
                    title: 'Widget is closing🔒',
                    text: 'Widget is closing',
                })

            }
        }
    };

    var checkout = new KhaltiCheckout(config);
    var btn = document.getElementById("payment-button");
    btn.onclick = function() {
        // Minimum transaction amount must be 10, i.e., 1000 in paisa.
        checkout.show({
            amount: total * 100
        });
    }
    </script>

    <script>
    // Disable right click
    document.addEventListener('contextmenu', (e) => e.preventDefault());

    function ctrlShiftKey(e, keyCode) {
        return e.ctrlKey && e.shiftKey && e.keyCode === keyCode.charCodeAt(0);
        alert("Ctrl+Shift+Key Disabled");
    }

    document.onkeydown = (e) => {
        // Disable F12, Ctrl + Shift + I, Ctrl + Shift + J, Ctrl + U
        if (
            event.keyCode === 123 ||
            ctrlShiftKey(e, 'I') ||
            ctrlShiftKey(e, 'J') ||
            ctrlShiftKey(e, 'C') ||
            (e.ctrlKey && e.keyCode === 'U'.charCodeAt(0))
        )
            return false;
    };
    </script>

</body>

</html>