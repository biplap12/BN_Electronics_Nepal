<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location: login.php');
    exit();
}
try {
    $id = $_GET['id'];
    include "../components/connect.php";
   $admin_id = $_SESSION['admin_id'];
    
        $order_status = "Shipped";
        $order_status = filter_var($order_status, FILTER_SANITIZE_STRING);
        
        $update_payment = $conn->prepare("UPDATE `orders` SET order_status = ?, shipped_order_inserter= ? WHERE id = ?");
        $update_payment->execute([$order_status, $admin_id, $id]);
    

    sleep(2);

    // Assuming you want to redirect, use header
    header('location: placed_orders.php');
    $emailSent = $conn->prepare("SELECT users.email, users.address, users.city, users.state, users.name, orders.id, orders.idx, orders.placed_on, orders.total_price, orders.total_products, orders.method, orders.payment_status FROM orders INNER JOIN users ON orders.user_id = users.id WHERE orders.id = ? LIMIT 1");
    $emailSent->execute([$id]);
    $row = $emailSent->fetch(PDO::FETCH_ASSOC); // Fetch the data into $row

    $email = $row['email']; // Use $row to get the 'email' value
    
    $to = $email; // Use $email as the recipient
    $subject = "Order Shipped - BN Electronics Nepal";    
    // HTML table for order details
    $message = "
        <p>Hello {$row['name']},</p>
        <p><strong>Your order with BN Electronics Nepal has been shipped. </strong></p>
        <p>Best regards,</p>
        <p>BN Electronics Nepal</p>
        <p  style='text-align: center;'>Thank you for shopping with us.❤️ </p>
    ";
    
    $headers = "From: BN Electronics Nepal\r\n";
    $headers .= "Reply-To: blood.bank.nepal11@gmail.com\r\n";
    $headers .= "Content-Type: text/html\r\n";
    $senderName = "BN Electronics Nepal";
    $mailSent = mail($to, $subject, $message, $headers);
    exit(); 
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>