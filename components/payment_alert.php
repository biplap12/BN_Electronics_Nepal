<?php
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders information based on user_id
$paymessage = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
$paymessage->execute([$user_id]);
if($paymessage->rowCount() > 0)
{
    $paymessage = $paymessage->fetch(PDO::FETCH_ASSOC);
$paymessage = $paymessage ? $paymessage : [];

// Check if the payment method is 'Cash On Delivery' and both 'idx' and 'token' are empty
// if ($paymessage['method'] === 'cash-on-delivery' && (empty($paymessage['token']) || empty($paymessage['idx']))) {
    
    // Do nothing if the conditions are met
    if (empty($paymessage['method']) || ($paymessage['method'] === 'khalti' || $paymessage['method'] === 'esewa' && !empty($paymessage['idx']) && !empty($paymessage['token']))) {
        // Show SweetAlert popup if all columns are empty
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Alert</title>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
    /* Add your custom styles here */
    .custom-swal-popup {
        width: 400px;
    }

    .custom-swal-title {
        font-size: 24px;
    }

    .custom-swal-content {
        font-size: 20px;
    }

    .custom-swal-icon {
        font-size: 40px;
        margin-bottom: 20px;
    }

    .custom-swal-button {
        padding: 20px 40px;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show SweetAlert popup
        Swal.fire({
            title: "Payment Required",
            text: "You need to make a payment before proceeding.",
            icon: "info",
            confirmButtonText: "Proceed to Payment",
            allowOutsideClick: false,
            customClass: {
                popup: 'custom-swal-popup',
                title: 'custom-swal-title',
                content: 'custom-swal-content',
                icon: 'custom-swal-icon',
                confirmButton: 'custom-swal-button',
            },
        }).then((result) => {
            // Redirect to payment.php if the user clicks the "Proceed to Payment" button
            if (result.isConfirmed) {
                window.location.href = "payment.php";
            }
        });
    });
    </script>
</head>

<body>
    <!-- Your HTML content goes here -->
</body>

</html>
<?php 
}}


?>