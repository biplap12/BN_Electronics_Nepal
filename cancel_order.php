<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
include 'admin/blocked_user.php';

if (isset($_GET['cancel_order'])) {
    $id = $_GET['cancel_order'];

    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
    $select_orders->execute([$id]);
    $order = $select_orders->fetch(PDO::FETCH_ASSOC);

    $orderStatus = $order['order_status'];
    $paymentMethod = $order['method'];
    $paymentStatus = $order['payment_Status'];

    // Assuming you want to cancel only if order status and payment status are 'Pending'
    if ($orderStatus == 'Pending' && $paymentStatus == 'Pending' && ($paymentMethod == 'cash-on-delivery' || empty($paymentMethod))) {
        $update_payment = $conn->prepare("UPDATE `orders` SET order_status = ?, payment_Status = ? WHERE id = ?");
        $update_payment->execute(['Cancelled', 'Cancelled', $id]);
        $update_stock = $conn->prepare("UPDATE products
        INNER JOIN orders_items ON products.id = orders_items.pid
        SET products.P_quantity = products.P_quantity + orders_items.order_qty
        WHERE orders_items.oid = ?");
        $update_stock->execute([$id]);
        $zero_stock = $conn->prepare("UPDATE orders_items SET order_qty = ? WHERE oid = ?");
        $zero_stock->execute(["0", $id]);

        $send_email = $conn->prepare("SELECT users.email FROM users INNER JOIN orders ON users.id = orders.user_id WHERE orders.id = ?");
        $send_email->execute([$id]);
        $row = $send_email->fetch(PDO::FETCH_ASSOC);

        $email = $row['email'];
        $to = $email;
        $subject = "Your order has been cancelled";
        $body = "<h1>Your order has been cancelled</h1> <br><br> <p>Hi " . $name . ",</p><br> <p>We are sorry that the item from order <strong># " . $id ."</strong> has been cancelled. Your cancellation reason is Payment not completed on time . </p><br> <p>Regards,<br> <strong>BN Electronics Nepal </strong></p>";
        $headers = "From: BN Electronics Nepal\r\n";
        $headers .= "Reply-To: blood.bank.nepal11@gmail.com\r\n";
        $headers .= "Content-Type: text/html\r\n";
        $mailSent = mail($to, $subject, $body, $headers);

        
    } else {
        http_response_code(400);
        echo "Order cannot be cancelled under current conditions.";
    }   


    header('location: orders.php');
    exit();
} elseif (isset($_GET['delete_order'])) {
    $id = $_GET['delete_order'];

    $send_email = $conn->prepare("SELECT users.email, orders.order_status, orders.payment_Status, orders.method FROM users INNER JOIN orders ON users.id = orders.user_id WHERE orders.id = ?");
    $send_email->execute([$id]);
    $row = $send_email->fetch(PDO::FETCH_ASSOC);

    // Check if the order can be deleted
    if (
        $row['order_status'] != 'Cancelled' &&
        $row['payment_Status'] != 'Cancelled' &&
        $row['payment_Status'] != '' &&
        $row['method'] != 'NULL'
    ) {
        http_response_code(400);
        exit();
    }

    $updateorderItems = $conn->prepare("UPDATE `orders_items` SET oid = ? WHERE oid = ?");
    $updateorderItems->execute([$oid, $id]);
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$id]);

    // Send an email to the user if the order has been deleted
    $email = $row['email'];
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $to = $email;
        $subject = "Your order has been deleted";
        $body = "<h1>Your order has been deleted</h1> <br><br> <p>Hi " . $name . ",</p><br> <p>We are sorry that the item from order <strong># " . $id ."</strong> has been deleted. </p><br> <p>Regards,<br> <strong>BN Electronics Nepal </strong></p>";
        $headers = "From: BN Electronics Nepal\r\n";
        $headers .= "Reply-To: blood.bank.nepal11@gmail.com\r\n";
        $headers .= "Content-Type: text/html\r\n";
        mail($to, $subject, $body, $headers);
    }

    header('location: orders.php');
    exit();
} else {
    http_response_code(400);
    exit();
}
?>