<?php


function getAuthToken($getTokenURL, $correlationID, $merchantID, $userId, $password) {
  $getTokenData     = [
          "SecurityKey" => "}tqNFE9Hr5Q87}sJ",
  ];
  $getTokenDataJson = json_encode($getTokenData);

  $getTokenHeaders = [
          "CorrelationID: " . $correlationID,
          "MerchantID: " . $merchantID,
          "UserId: " . $userId,
          "Password: " . $password,
          "Content-Type: application/json",
  ];

  $ch = curl_init($getTokenURL);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $getTokenDataJson);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $getTokenHeaders);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

  $tokenResponse = curl_exec($ch);
  if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    exit;
  }

  curl_close($ch);

  return json_decode($tokenResponse, TRUE);
}

function makePaymentRequest($paymentRequestURL, $correlationID, $merchantID, $userId, $password, $token) {
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
          "MessageTrxID" => $correlationID . "456",
          "MerchantID"   => $merchantID,
          "RAliasType"   => $_POST['RAliasType'], // Replace with the selected form value
          "RAliasValue"  => $_POST['RAliasValue'], // Replace with the user input
          "Amount"       => "5.0", // Replace with the amount from another API
  ];

  // Convert the request data to JSON
  $paymentRequestDataJson = json_encode($paymentRequestData);
  // Initialize cURL session for the PaymentRequest request
  $ch = curl_init($paymentRequestURL);

  // Set cURL options for the PaymentRequest request
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $paymentRequestDataJson);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $paymentRequestHeaders);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

  // Execute the PaymentRequest request
  $paymentResponse = curl_exec($ch);

  // Check for cURL errors
  if (curl_errno($ch)) {
    echo "\ncURL error: " . $ch;
    exit;
  }

  // Close the cURL session for the PaymentRequest request
  curl_close($ch);

  // Parse the PaymentRequest response JSON
  return $paymentResponse;

}


// Main code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['RAliasType'], $_POST['RAliasValue'])) {
    $correlationID = uniqid();
    $merchantID    = "AVOCADO1";
    $userId        = "UWallet";
    $password      = "wyxthKzbGn";

    $getTokenURL = "https://testapi.uwallet.jo/A2AMerchantInterface/GetToken";

    $paymentRequestURL = "https://testapi.uwallet.jo/A2AMerchantInterface/Purchase";

    $tokenResponseData = getAuthToken($getTokenURL, $correlationID, $merchantID, $userId, $password);

    if (isset($tokenResponseData['TokenInfo']['Token'])) {
      $token = $tokenResponseData['TokenInfo']['Token'];

      $paymentResponseData = makePaymentRequest($paymentRequestURL, $correlationID, $merchantID, $userId, $password, $token);

      echo json_encode($paymentResponseData);
    } else {
      echo "{\"error\" : \"Failed to obtain a token. Check the getToken API response.\"}";

    }
  }
}

