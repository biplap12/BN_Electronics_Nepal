<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f8f8;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .payment-failure-container {
        text-align: center;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .icon {
        font-size: 60px;
        color: #ff4d4f;
    }

    h1 {
        color: #ff4d4f;
    }

    p {
        color: #333333;
    }

    button {
        margin-top: 20px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        background-color: #1890ff;
        color: #ffffff;
        border: none;
        border-radius: 4px;
    }
    </style>
</head>

<body>
    <div class="payment-failure-container">
        <div class="icon">&#x26D4;</div>
        <h1>Payment Failed</h1>
        <p>Oops! Something went wrong with your payment.</p>
        <p>Please check your payment details and try again.</p>
        <button onclick="retryPayment()">Retry Payment</button>
    </div>

    <script>
    function retryPayment() {
        // Add functionality to handle retrying the payment
        window.location.href = "orders.php";
    }
    </script>
</body>

</html>