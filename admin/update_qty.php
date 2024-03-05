<?php
include_once '../components/connect.php';

try {
    if (isset($_POST['update_qty_id']) && isset($_POST['update_qty'])) {
        // Get data from POST request
        $id = $_POST['update_qty_id'];
        $newQuantity = $_POST['update_qty'];

        // Prepare and execute the SQL update statement
        $statement = $conn->prepare('UPDATE `products` SET `P_quantity` = :newQuantity WHERE id = :id');
        $statement->execute(['newQuantity' => $newQuantity, 'id' => $id]);

        $rowCount = $statement->rowCount();

        if ($rowCount > 0) {
            echo json_encode(['status' => 'success']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No rows affected. Update failed.']);
            exit;
        }
    }
} catch (PDOException $e) {
    // Send error response and exit
    echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $e->getMessage()]);
    exit;
}

try {
    include "../components/connect.php";

    // Fetch suggestions based on the input
    if (isset($_POST['input'])) {
        $input = $_POST['input'];

        // Prepare a SELECT statement with LIKE clause
        $stmt = $conn->prepare("SELECT * FROM users WHERE email LIKE :input");
        $stmt->bindValue(':input', '%' . $input . '%', PDO::PARAM_STR);
        $stmt->execute();
        $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $username=$conn->prepare("SELECT * FROM users WHERE email = ?");
        $username->execute([$input]);
        $user=$username->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['suggestions' => $suggestions,'user'=>$user]);

        // Return suggestions as JSON
        // echo json_encode(['suggestions' => $suggestions]);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Close the connection (optional, as PDO closes the connection automatically when the script ends)
    $conn = null;
}
?>