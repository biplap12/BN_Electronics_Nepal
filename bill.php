<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if (isset($_GET['view_bill'])) {
$order_id = $_GET['view_bill'];

$sucessOrders = $conn->prepare("SELECT users.name, users.email, users.phone,users.city,users.state, users.address, orders.id,
orders.total_products, orders.total_price,orders.idx,orders.invoice_no,orders.order_status, orders.method,orders.payment_Status, orders.placed_on FROM users INNER JOIN orders ON users.id =
orders.user_id WHERE orders.id = ?");
$sucessOrders->execute([$order_id]);
if ($sucessOrders->rowCount() > 0) {
while ($fetch_ordersDetails = $sucessOrders->fetch(PDO::FETCH_ASSOC)) {
$Ordersid = $fetch_ordersDetails['id'];
$invoice_no = $fetch_ordersDetails['invoice_no'];
$OrdersName = $fetch_ordersDetails['name'];
$OrdersEmail = $fetch_ordersDetails['email'];
$OrdersPhone = $fetch_ordersDetails['phone']; // Assuming the phone number is stored in the 'number' column
$state = $fetch_ordersDetails['state'];
$city = $fetch_ordersDetails['city'];
$order_status = $fetch_ordersDetails['order_status'];
$OrdersAddress = $fetch_ordersDetails['address'];
$OrdersTotalProducts = $fetch_ordersDetails['total_products'];
$OrdersTotalPrice = $fetch_ordersDetails['total_price'];
$OrdersPlaced_on = $fetch_ordersDetails['placed_on'];
$OrdersMethod = $fetch_ordersDetails['method'];
$Orderpayment_Status = $fetch_ordersDetails['payment_Status'];
$OrdersTransation = $fetch_ordersDetails['idx'];
    
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BN Electronic Nepal || Receipt</title>
    <link rel="stylesheet" href="./css/view_order.css?<?php echo Time();?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

</head>
<style>
.receipt {
    width: 100%;
    max-width: 700px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
    line-height: 24px;
}
</style>

<body>

    <div class="receipt">
        <a onclick="return backbtn()" href="javascript:void(0)" class="xlose"><i class="fa-solid fa-x"></i></a>
        <div class="header">
            <img src="./images/bn-electronics-logo.png" width="70px" alt="BN Electronic Nepal Logo">
            <h1>BN Electronic Nepal</h1>
            <p>Nayabazar 16, Kathmandu Nepal</p>
        </div>
        <div class="info">
            <p><strong>INVOICE NO.: </strong><span><?=$invoice_no;?></span></p>
            <?php
            if($OrdersMethod === 'khalti' || $OrdersMethod === 'esewa'){
                echo "<p><strong>TRAN.ID.:</strong> <span class='paymentstatus success' >$OrdersTransation</span></p>";
            }
?>
            <p><strong>DATE:</strong> <span><?=$OrdersPlaced_on?></span></p>
        </div>
        <div class="bill_ship">
            <div class="bill-to">
                <h3>BILL TO:</h3>
                <p><strong>COMPANY NAME:</strong>BN Electronic Nepal</p>
                <p><strong>ADDRESS:</strong>Nayabazar Kathmandu Nepal </p>
                <p><strong>PHONE:</strong> 9812345678</p>
                <p><strong>EMAIL:</strong> bnelectronic@gmail.com</p>
                <?php
                if($order_status === 'Completed'){
                    echo "<p><strong>Order Status:</strong> <span class='paymentstatus success' >$order_status</span></p>";
                }elseif($order_status === 'Shipped'){
                    echo "<p><strong>Order Status:</strong> <span class='paymentstatus warning' >$order_status</span></p>";
                }elseif($order_status === 'Pending'){
                    echo "<p><strong>Order Status:</strong> <span class='paymentstatus danger' >$order_status</span></p>";
                }elseif($order_status === 'Cancelled'){
                    echo "<p><strong>Order Status:</strong> <span class='paymentstatus default' >$order_status</span></p>";
                }else{
                    echo "<p><strong>Order Status:</strong> <span class='paymentstatus danger' >$order_status</span></p>";
                }
                ?>
            </div>
            <div class="ship-to">
                <h3>SHIP TO:</h3>
                <p><strong>NAME:</strong> <?= $OrdersName; ?></p>
                <p><strong>ADDRESS:</strong><?= $OrdersAddress .','. $city .','. $state?></p>
                <p><strong>PHONE:</strong><?= $OrdersPhone;?></p>
                <p><strong>EMAIL:</strong><?= $OrdersEmail;?></p>
                <?php
            if ($Orderpayment_Status === 'Success') {
                echo "<p><strong>Payment Status:</strong> <span class='paymentstatus success' >$Orderpayment_Status</span></p>";
            } else {
                echo "<p><strong>Status:</strong> <span class='paymentstatus danger' >$Orderpayment_Status</span></p>";
            }
            ?>
            </div>
        </div>
        <?php 
             

        $data = $OrdersTotalProducts; // Replace this with your actual data
        $entries = explode(',', $data);
        
        // Start the table
        echo "<table class='items-table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>DESCRIPTION</th>";
        echo "<th>QTY</th>";
        echo "<th>UNIT PRICE</th>";
        echo "<th>TOTAL</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        $totalSum = 0;
        
        foreach ($entries as $index => $entry) {
            // Extract values from each entry
            if (preg_match('/(\w+) \((\d+) x (\d+)\)/', trim($entry), $matches)) {
                // Assuming you have variables for description, quantity, unit price, and total
                list(, $description, $qty, $unitPrice) = $matches;
        
                // Assuming $total is calculated based on your logic
                $total = $qty * $unitPrice;
        
                // Add to the total sum
                $totalSum += $total;
        
                // Output a table row for each entry
                echo "<tr>";
                echo "<td>" . ($index + 1) . "</td>"; // Add 1 to the index to start from 1
                echo "<td>" . $description . "</td>";
                echo "<td>" . $unitPrice . "</td>";
                echo "<td>" . $qty . "</td>";
                echo "<td>" . $total . "</td>";
                echo "</tr>";
            }
        }
        
        // Add a row for the total sum
        echo "<tr>";
        echo "<td colspan='4' style='text-align:right;'><strong>Total:</strong></td>";
        echo "<td>" . $totalSum . "</td>";
        echo "</tr>";
        
        // Close the table
        echo "</tbody>";
        echo "</table>";
        
        $shipping = 0;
        $shipping = $OrdersTotalPrice -$totalSum . '.00';
        ?>
        <div class="summary_notes">
            <div class="notes">
                <p>REMARKS/NOTES:</p>
                <div class="note">
                    <p><strong>NOTES:</strong></p>
                    <?php
                    if($OrdersMethod !== ""){
                    if ($OrdersMethod === 'cash-on-delivery') {
                        echo "<p> The customer will pay the full amount when the product is delivered.</p>";
                    } else {
                        echo "<p> The customer paid the full amount.</p>";
                    }
                    
                    if ($OrdersMethod === 'Khalti' || $OrdersMethod === 'Esewa') {
                        echo "<p><strong>Payment Method:</strong> <br> <span class='paymentstatus default_color' >$OrdersMethod</span></p>";
                    } 
                    elseif ($OrdersMethod === 'cash-on-delivery') {
                        echo "<p><strong>Payment Method:</strong> <br> <span class='paymentstatus danger_color' >$OrdersMethod</span></p>";
                    }
                    else {
                        echo "<p><strong>Payment Method:</strong> <br> <span class='paymentstatus danger_color' >$OrdersMethod</span></p>";
                    }
                }   else
                {
                    echo "<p class='danger_color' ><strong>No payment method selected.</strong></p>";
                }
                        ?>

                </div>
            </div>
            <div class="summary">
                <p><strong>SUBTOTAL:</strong> <span>Rs <?=$totalSum ?></span></p>
                <p><strong>DISCOUNT %:</strong> <span>0%</span></p>
                <p><strong>DISCOUNT:</strong> <span>Rs 00.00</span></p>
                <p><strong>TAX RATE:</strong> <span>0%</span></p>
                <p><strong>TOTAL TAX:</strong> <span>Rs 0.00</span></p>
                <p><strong>SHIPPING:</strong> <span>Rs <?=$shipping?></span></p>
                <p><strong>TOTAL:</strong> <span><?=$OrdersTotalPrice?></span></p>

            </div>
        </div>
        <h6> <strong>In Words:</strong> <i id="word_payment"></i> </h6>

        <footer>
            <div class="backbutton">
                <button onclick="backbtn()"><i class="fa-solid fa-left-long"></i></button>
            </div>
            <p>Thank you for shopping with us !</p>
            <button id="printBtn">Print</button>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    document.getElementById("printBtn").onclick = function() {
        window.print();
    }

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
    const numericValue = <?=$OrdersTotalPrice?>;
    const wordRepresentation = convertNumberToWords(numericValue);
    document.getElementById('word_payment').innerHTML = (
        wordRepresentation +
        " only .");

    function backbtn() {
        Swal.fire({
            icon: 'info',
            title: 'Please Wait...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            timer: 3000,
            customClass: {
                popup: 'larger-swal',
                title: 'larger-swal-title',
                content: 'larger-swal-content',
                confirmButton: 'larger-swal-confirm-button',
                loader: 'larger-swal-loader', // Add a custom class for the loader


            },
            onBeforeOpen: () => {
                Swal.showLoading();
                $(".receipt").css({
                    opacity: "0.2",
                    pointerEvents: "none"
                });
            }
        }).then((result) => {
            window.location.href = 'orders.php';

        });
    }
    </script>

</body>

</html>