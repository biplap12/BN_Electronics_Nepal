<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
}
if (isset($_GET['Payment_id'])) {
$order_id = $_GET['Payment_id'];

$sucessOrders = $conn->prepare("SELECT users.name, users.email, users.phone,users.city,users.state, users.address, orders.id,
orders.total_products, orders.total_price, orders.method,orders.idx,orders.payment_Status,orders.invoice_no, orders.placed_on FROM users INNER JOIN orders ON users.id =
orders.user_id WHERE orders.id = ?");
$sucessOrders->execute([$order_id]);

if ($sucessOrders->rowCount() > 0) {
while ($fetch_ordersDetails = $sucessOrders->fetch(PDO::FETCH_ASSOC)) {
$Ordersid = $fetch_ordersDetails['id'];
$OrdersName = $fetch_ordersDetails['name'];
$OrdersAddress = $fetch_ordersDetails['address'];
$Ordersstate = $fetch_ordersDetails['state'];
$Orderscity = $fetch_ordersDetails['city'];
$OrdersPayment_id = $fetch_ordersDetails['idx'];
$OrdersTotalProducts = $fetch_ordersDetails['total_products'];
$OrdersTotalPrice = $fetch_ordersDetails['total_price'];
$OrdersMethod = $fetch_ordersDetails['method'];
$OrdersPlaced_on = $fetch_ordersDetails['placed_on'];
$OrdersPaymentStatus = $fetch_ordersDetails['payment_Status'];
$invoice_no =$fetch_ordersDetails['invoice_no'];
}
} else {

echo "Order not found";
exit();
}}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <!-- Add your styles or link to a CSS file here -->
    <link rel="stylesheet" href="style1.css?<?php echo Time()?> ">
    <link rel="stylesheet" href="css/payment.css?<?php echo Time();?>  ">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>
    <div class="container">
        <div class="close_btn">
            <a class="close" href="orders.php"><i class="fa-solid fa-circle-xmark"></i></a>
        </div>
        <?php 
            $heading = ($OrdersMethod === 'cash-on-delivery') ? 'Cash on Delivery' : 'Payment Successful!!';
            $customerName =  htmlspecialchars($OrdersName) ;

?>
        <div class="logo_Com_info">
            <img src="images/bn-electronics-logo.png" alt="BN Electronics Nepal logo">
            <h1>BN Electronics Nepal</h1>
            <p>Nayabazar, Kathmandu Bagamati Nepal</p>
        </div>
        <h1><?php echo $heading; ?></h1>
        <p><strong>Thank you <?php echo $customerName; ?></strong>,
            for your purchase. Your order has been successfully
            placed. Your
            order will be delivered as soon as possible.
        </p>

        <h2>Order Details:</h2>
        <table>
            <tr>
                <td><strong>Invoice No.:</strong></td>
                <td><?= htmlspecialchars($invoice_no) ?></td>
            </tr>
            <?php
if ($OrdersMethod === 'Khalti' || $OrdersMethod === 'Esewa') {
    echo '<tr>
        <td><strong>Transaction Id:</strong></td>
        <td><strong>' . htmlspecialchars($OrdersPayment_id) . '</strong></td>
    </tr>';
    
    echo '<tr>
        <td><strong>Payment Status:</strong></td>';
    
    $borderColor = (htmlspecialchars($OrdersPaymentStatus) == 'Success') ? '#00ff00' : '#ff0000';
    $paymentStatusColor = ($borderColor == '#00ff00') ? '#00ff00' : '#ff0000';
    
    echo '<td><strong>
            <span style="color: ' . $paymentStatusColor . '; text-align: center; display: inline-block; width: 150px;
                height: 15px; border: 2px solid ' . $borderColor . '; padding: 1px;">
                <i class="fa-solid fa-circle-check" style="color: ' . $borderColor . ';"></i>
                ' . htmlspecialchars($OrdersPaymentStatus) . '
            </span>
        </strong></td>';
}
?>


            <tr>
                <td><strong>Order Date:</strong></td>
                <td><?= htmlspecialchars($OrdersPlaced_on) ?></td>
            </tr>
            <tr>
                <td><strong>Name:</strong></td>
                <td><?= htmlspecialchars($OrdersName) ?></td>
            </tr>

            <tr>
                <td><strong>Address:</strong></td>
                <td><?= htmlspecialchars($OrdersAddress.','.$Orderscity.','.$Ordersstate) ?></td>
            </tr>
            <tr>
                <td><strong>Total Products:</strong></td>
                <td><?= htmlspecialchars($OrdersTotalProducts) ?></td>
            </tr>
            <tr>


                <td><strong>Total Price:</strong></td>
                <td style="font-weight: bold;"><?= htmlspecialchars('Rs ' . $OrdersTotalPrice) . ' /-' ?></td>
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
                <td><strong>
                        <span style="color: <?php echo $paymentStatusColor; ?>;text-align: center; display: inline-block; width: 150px;
                         height: 15px; border: 2px solid <?php echo $borderColor; ?>; padding: 1px;">
                            <i class="fa-solid fa-circle-check" style="color: <?php echo $borderColor; ?>;"></i>
                            <?php echo htmlspecialchars($OrdersMethod); ?>
                        </span></strong>
                </td>
            </tr>
        </table>
        <div class="buttons">
            <button id="Go_to_My_Account"><a href="orders.php"><i class="fa-solid fa-left-long"></i></a></button>
            <button id="print_Payment_bill"><i class="fa-solid fa-print"></i></button>
        </div>
    </div>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

    <?php
include_once 'admin/send_Bill_In_Email.php';
?>
</body>


</html>