<?php

// // $url = "https://khalti.com/api/v2/merchant-transaction/JupAYewfs5QW2Bid5wMnfF/";

// // # Make the call using API.
// // $ch = curl_init();
// // curl_setopt($ch, CURLOPT_URL, $url);
// // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// // $headers = ['Authorization: Key test_secret_key_67c35d31456545dfa734f7f1ea215229'];
// // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// // // Response
// // $response = curl_exec($ch);
// // $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// // curl_close($ch);
// // echo $response;
// // $data = json_decode($response, true);


// // // Get state name
// // $stateName = $data['state']['name'];

// // // Output state name
// // echo "State Name: $stateName";


// // get.php

// // Check if the required parameters are set in the URL
// if (isset($_GET['idx']) && isset($_GET['paymentID']) && isset($_GET['amount']) && isset($_GET['productName']) && isset($_GET['token'])) {
//     // Retrieve data from the URL parameters
//     $idx = $_GET['idx'];
//     $paymentID = $_GET['paymentID'];
//     $amount = $_GET['amount'];
//     $productName = $_GET['productName'];
//     $token = $_GET['token'];

//     // Perform any processing with the retrieved data
//     // For example, you might want to log the data to a file, store it in a database, etc.

//     // Send a response (optional)
//     $response = array('status' => 'success', 'message' => 'Data received successfully');
//     header('Content-Type: application/json');
//     echo json_encode($response);
// } else {
//     // Handle the case where one or more parameters are missing
//     $response = array('status' => 'error', 'message' => 'Missing parameters');
//     header('Content-Type: application/json');
//     echo json_encode($response);
// }
?>

<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
}
// echo '<pre>';
// print_r($_POST);
// die;
// echo '</pre>';
$sucessOrders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
$sucessOrders->execute([$user_id]);

if ($sucessOrders->rowCount() > 0) {
    while ($fetch_ordersDetails = $sucessOrders->fetch(PDO::FETCH_ASSOC)) {
        $Ordersid = $fetch_ordersDetails['id'];
        $OrdersName = $fetch_ordersDetails['name'];
        $OrdersEmail = $fetch_ordersDetails['email'];
        $OrdersPhone = $fetch_ordersDetails['number']; // Assuming the phone number is stored in the 'number' column
        $OrdersAddress = $fetch_ordersDetails['address'];
        $OrdersTotalProducts = $fetch_ordersDetails['total_products'];
        $OrdersTotalPrice = $fetch_ordersDetails['total_price'];
        $OrdersMethod = $fetch_ordersDetails['method'];
        $OrdersPlaced_on = $fetch_ordersDetails['placed_on'];
        }
} else {
   
    echo "Order not found";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <!-- Add your styles or link to a CSS file here -->
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <style>
    .container {
        width: 100%;
        max-width: 600px;
        margin: 50px auto;
        display: flex;
        flex-direction: column;

    }

    .container div {
        margin: 20px 0;
        display: flex;
        justify-content: space-between;
    }

    #print_Payment_bill {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        font-size: 16px;
        cursor: pointer;
    }

    #Go_to_My_Account {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 25px;
        text-decoration: none;
        font-size: 16px;
        cursor: pointer;
    }

    #Go_to_My_Account a {
        color: white;
        text-decoration: none;
    }

    @media print {

        #print_Payment_bill,
        #Go_to_My_Account {
            display: none;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <?php 
$heading = ($OrdersMethod === 'cash-on-delivery') ? 'Cash on Delivery' : 'Payment Successful!';
$customerName =  htmlspecialchars($OrdersName) ;

?>

        <h1><?php echo $heading; ?></h1>
        <p><strong>Thank you <?php echo $customerName; ?></strong>,
            for your purchase. Your order has been successfully
            placed. Your
            order will be delivered as soon as possible.
        </p>

        <h2>Order Details:</h2>
        <table>
            <tr>
                <td><strong>Order ID:</strong></td>
                <td><?= htmlspecialchars($Ordersid) ?></td>
            </tr>
            <tr>
                <td><strong>Order Date:</strong></td>
                <td><?= htmlspecialchars($OrdersPlaced_on) ?></td>
            </tr>
            <tr>
                <td><strong>Name:</strong></td>
                <td><?= htmlspecialchars($OrdersName) ?></td>
            </tr>
            <tr>
                <td><strong>Phone Number:</strong></td>
                <td><?= htmlspecialchars($OrdersPhone) ?></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td><?= htmlspecialchars($OrdersEmail) ?></td>
            </tr>
            <tr>
                <td><strong>Address:</strong></td>
                <td><?= htmlspecialchars($OrdersAddress) ?></td>
            </tr>
            <tr>
                <td><strong>Total Products:</strong></td>
                <td><?= htmlspecialchars($OrdersTotalProducts) ?></td>
            </tr>
            <tr>


                <td><strong>Total Price:</strong></td>
                <td style="font-weight: bold;"><?= htmlspecialchars($OrdersTotalPrice) . ' /-' ?></td>
            </tr>
            <tr>
                <td><strong>In Word:</strong></td>
                <td id="word_payment" style="text-decoration:underline; font-weight: bold; "></td>
            </tr>

            <tr>
                <td><strong>Payment Method:</strong></td>
                <?php
$borderColor = (htmlspecialchars($OrdersMethod) == 'cash-on-delivery') ? '#ff0000' : '#00ff00';
$paymentStatusColor = ($borderColor == '#00ff00') ? '#00ff00' : '#ff0000';
?>
                <td>
                    <span style="color: <?php echo $paymentStatusColor; ?>;text-align: center; display: inline-block; width: 150px;
                         height: 15px; border: 2px solid <?php echo $borderColor; ?>; padding: 1px;">
                        <i class="fa-solid fa-circle-check" style="color: <?php echo $borderColor; ?>;"></i>
                        <?php echo htmlspecialchars($OrdersMethod); ?>
                    </span>
                </td>
            </tr>
        </table>
        <div>
            <button id="Go_to_My_Account"><a href="orders.php">Go to My Account</a></button>
            <button id="print_Payment_bill">Print</button>
        </div>

    </div>
    <script>
    document.getElementById("print_Payment_bill").addEventListener("click", function() {
        window.print();
    });

    function convertNumberToWords(number) {
        const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const teens = ['', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen',
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
    const numericValue = <?=$OrdersTotalPrice?>;
    const wordRepresentation = convertNumberToWords(numericValue);
    document.getElementById('word_payment').innerHTML = (
        wordRepresentation +
        " only ");
    </script>
</body>


</html>