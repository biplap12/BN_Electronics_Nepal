<?php
$response = '{
    "idx": "8xmeJnNXfoVjCvGcZiiGe7",
    "type": {
      "idx": "e476BL6jt9kgagEmsakyTL",
      "name": "Wallet payment"
    },
    "state": {
      "idx": "DhvMj9hdRufLqkP8ZY4d8g",
      "name": "Completed",
      "template": "is complete"
    },
    "amount": 1000,
    "fee_amount": 30,
    "refunded": false,
    "created_on": "2018-06-20T14:48:08.867125+05:45",
    "ebanker": null,
    "user": {
      "idx": "cCaPkRPQGn5D8StkiqqMJg",
      "name": "Test User",
      "mobile": "98XXXXXXX9"
    },
    "merchant": {
      "idx": "UM75Gm2gWmZvA4TPwkwZye",
      "name": "Test Merchant",
      "mobile": "testmerchant@khalti.com"
    }
  }';
  
  // Decode the JSON response
  $data = json_decode($response, true);
  
  // Check if decoding was successful
      // Access the "state" object and then its "name" property
      $paymentState = $data['state']['name'];
      echo $paymentState;
  
     
  
?>