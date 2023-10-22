<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['RAliasType'], $_POST['RAliasValue'])) {
    // Generate a UUID for CorrelationID
    $correlationID = uniqid();

    // Your machine ID, username, and password
    $merchantID = "your_machine_id";
    $userId = "your_username";
    $password = "your_password";

    // Data for the getToken API request
    $getTokenData = [
            "SecurityKey" => "jkadfj;aldsjf;ldaj",
    ];

// Convert the request data to JSON
    $getTokenDataJson = json_encode($getTokenData);

// URL for the getToken API
    $getTokenURL = "https://xxxx/getToken";

// Set the headers for the getToken API request
    $getTokenHeaders = [
            "CorrelationID: " . $correlationID,
            "MerchantID: " . $merchantID,
            "UserId: " . $userId,
            "password: " . $password,
            "Content-Type: application/json",
    ];

// Initialize cURL session for the getToken request
    $getTokenCh = curl_init($getTokenURL);

// Set cURL options for the getToken request
    curl_setopt($getTokenCh, CURLOPT_POST, 1);
    curl_setopt($getTokenCh, CURLOPT_POSTFIELDS, $getTokenDataJson);
    curl_setopt($getTokenCh, CURLOPT_HTTPHEADER, $getTokenHeaders);
    curl_setopt($getTokenCh, CURLOPT_RETURNTRANSFER, true);

// Execute the getToken request
    $tokenResponse = curl_exec($getTokenCh);

// Check for cURL errors
    if (curl_errno($getTokenCh)) {
      echo 'cURL error: ' . curl_error($getTokenCh);
      exit;
    }

// Close the cURL session for the getToken request
    curl_close($getTokenCh);

// Parse the response JSON from the getToken API
    $tokenResponseData = json_decode($tokenResponse, true);

// Check if the response contains a token
    if (isset($tokenResponseData["token"])) {
      $token = $tokenResponseData["token"];

// Set the headers for the PaymentRequest API request
      $paymentRequestHeaders = [
              "CorrelationID: " . $correlationID,
              "MerchantID: " . $merchantID,
              "UserId: " . $userId,
              "password: " . $password,
              "Authorization: Bearer " . $token,
              "Content-Type: application/json",
      ];

// Data for the PaymentRequest API request
      $paymentRequestData = [
              "MessagTrxID" => $correlationID,
              "MerchantID" => $merchantID,
              "RAliasType" => $_POST['RAliasType'],
              "RAliasValue" => $_POST['RAliasValue'],
              "Amount" => "5.0 JOD", // Set the amount
      ];

// Simulate processing the payment request (Replace with actual API call)
      $paymentResponse = [
              'status' => 'complete' // Replace with your logic to handle the response
      ];

// Simulate waiting for 5 seconds
      sleep(5);

      echo json_encode($paymentResponse);
      exit;
    } else {
      echo "Failed to obtain a token. Check the getToken API response.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payment Request Form</title>
  <style>
      body {
          font-family: Arial, sans-serif;
      }
      h1 {
          text-align: center;
      }
      form {
          width: 300px;
          margin: 0 auto;
      }
      label {
          display: block;
          margin-top: 10px;
      }
      select, input[type="text"] {
          width: 100%;
          padding: 10px;
          margin: 5px 0;
      }
      input[type="submit"] {
          background-color: #007BFF;
          color: white;
          border: none;
          padding: 10px 20px;
          cursor: pointer;
      }
      input[type="submit"]:hover {
          background-color: #0056b3;
      }
      #status {
          text-align: center;
          margin-top: 20px;
      }
  </style>
</head>
<body>
<h1>Payment Request Form</h1>
<form id="paymentForm" onsubmit="submitPaymentRequest(event)">
  <label for="RAliasType">Select Alias Type:</label>
  <select name="RAliasType" id="RAliasType" required>
    <option value="MOBL">Mobile Alias</option>
    <option value="ALIAS">Regular Alias</option>
  </select>
  <label for="RAliasValue">Alias Value:</label>
  <input type="text" name="RAliasValue" id="RAliasValue" required>
  <input type="hidden" name="Amount" value="5.0 JOD">
  <input type="submit" value="Submit Payment Request">
</form>

<div id="status"></div>

<script>
    function submitPaymentRequest(event) {
        event.preventDefault();
        const form = document.getElementById('paymentForm');
        const statusElement = document.getElementById('status');

        const formData = new FormData(form);

        fetch(window.location.href, {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'complete') {
                    statusElement.textContent = 'Payment Request processed successfully.';
                } else {
                    statusElement.textContent = 'Payment Request processing in progress...';
                    checkStatus();
                }
            });
    }

    function checkStatus() {
        fetch(window.location.href + '?check_status=1')
            .then(response => response.json())
            .then
