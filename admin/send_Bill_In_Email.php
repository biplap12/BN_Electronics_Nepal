<?php

if (isset($_GET['Payment_id'])) {
     $order_id = $_GET['Payment_id'];
  
    
    $emailSent = $conn->prepare("SELECT users.email, users.address, users.city, users.state, users.name, orders.id, orders.invoice_no, orders.idx, orders.placed_on, orders.total_price, orders.total_products, orders.method, orders.payment_status FROM orders INNER JOIN users ON orders.user_id = users.id WHERE orders.id = ? LIMIT 1");
    $emailSent->execute([$order_id]);
    
    if ($emailSent->rowCount() > 0) {
        $row = $emailSent->fetch(PDO::FETCH_ASSOC);
    
        $to = $row['email'];
        $subject = "Order Successful!! - BN Electronics Nepal🛒";
    
        // HTML table for order details
        $orderTable = "
        <div style='text-align: center;'>
        <table style='border-collapse: collapse; width:50%; border: 2px solid black; margin: auto;'>
            <tr>
            <td style='border:1px solid black;'><strong>Invoice No.:</strong></td>
            <td style='border:1px solid black;'>{$row['invoice_no']}</td>
        </tr>
        <tr>
            <td style='border:1px solid black;'><strong>Order Date:</strong></td>
            <td style='border:1px solid black;'>{$row['placed_on']}</td>
        </tr>
        <tr>
            <td style='border:1px solid black;'><strong>Products:</strong></td>
            <td style='border:1px solid black;'>{$row['total_products']}</td>
        </tr>
        <tr>
            <td style='border:1px solid black;'><strong>Total Amount:</strong></td>
            <td style='border:1px solid black;'>{$row['total_price']}</td>
        </tr>";

if ($row['method'] == 'cash-on-delivery') {
    echo'';
}else{
    $orderTable .= "
        <tr>
            <td style='border:1px solid black;'><strong>Payment ID:</strong></td>
            <td style='border:1px solid black;'>{$row['idx']}</td>
        </tr>";
}

$orderTable .= "
        <tr>
            <td style='border:1px solid black;'><strong>Payment Method:</strong></td>
            <td style='border:1px solid black;'><strong>{$row['method']}</strong></td>
        </tr>
        <tr>
            <td style='border:1px solid black;'><strong>Payment Status:</strong></td>
            <td style='border:1px solid black;'><strong>{$row['payment_status']}</strong></td>
        </tr>
        <tr>
            <td style='border:1px solid black;'><strong>Address:</strong></td>
            <td style='border:1px solid black;'>{$row['address']}, {$row['city']}, {$row['state']}</td>
        </tr>
    </table>
    </div>";

        // Replace placeholders in your message with actual values
        $message = "
            <p>Hello {$row['name']},</p>
            <p>Thank you for your order with BN Electronics Nepal. Your order details are as follows:</p>
            {$orderTable}
            <p>Best regards,</p>
            <p>BN Electronics Nepal</p>
        ";
    
        // Send the email
        $headers = "From:BN Electronics Nepal\r\n";
        $headers .= "Reply-To: blood.bank.nepal11@gmail.com\r\n";
        $headers .= "Content-Type: text/html\r\n";
        $mailSent = mail($to,  $subject, $message, $headers);

}}
exit();


?>