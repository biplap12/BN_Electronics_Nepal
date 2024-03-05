<?php
// update_order.php

include 'components/connect.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

try {
    $requiredFields = ['id', 'idx', 'token', 'method'];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Please fill all the required fields']);
            exit;
        }
    }

    $id = $_POST['id'];
    $idx = $_POST['idx'];
    $token = $_POST['token'];
    $method = $_POST['method'];
    $payment_Status = 'Success';

    $conn->beginTransaction();

    $update_payment = $conn->prepare("UPDATE `orders` SET `idx`=?,`token`=?, `payment_Status`= ?,  `method`=? WHERE id = ?");
    $update_payment->execute([$idx, $token, $payment_Status, $method, $id]);

    $conn->commit(); // Commit the transaction

    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Payment successfully']);
} catch (PDOException $e) {
    error_log($e->getMessage());

    // Rollback the transaction (if applicable)
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Payment update failed']);
    throw $e;
} finally {
    exit;
}


?>