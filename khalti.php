<?php
include 'components/connect.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    header('Location: home.php');
    exit;
}

try {
    $requiredFields = ['id', 'idx', 'token', 'method'];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Please fill all the required fields.']);
            exit;
        }
    }

    $id = $_POST['id'];
    $idx = $_POST['idx'];
    $token = $_POST['token'];
    $method = $_POST['method'];
    $payment_Status = 'Success';
    $amount = $_POST['amount'];
    
    $args = http_build_query(array(
    'token' => $token,
    'amount'  => $amount
    ));

    $url = "https://khalti.com/api/v2/payment/verify/";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $headers = ['Authorization: Key test_secret_key_67c35d31456545dfa734f7f1ea215229'];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // Response
            $response = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $data = json_decode($response, true);
     if ($status_code == 200 && $data['state']['name'] == 'Completed') {
        $conn->beginTransaction();
        $update_payment = $conn->prepare("UPDATE `orders` SET `idx`=?,`token`=?, `payment_Status`= ?,  `method`=? WHERE id = ?");
        $update_payment->execute([$idx, $token, $payment_Status, $method, $id]);
     $conn->commit(); 
     curl_close($ch);
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Your payment has been processed successfully.']);
    exit;
}
} catch (PDOException $e) {
    error_log($e->getMessage());
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Payment update failed']);
    throw $e;
} finally {
    exit;
}


?>