<?php
include ("TokenHandler.php");
include ("HeaderParams.php");
class PurchaseHandler {
  function merchantPurchaseRequest(){
    $url = " https://testapi.uwallet.jo/A2AMerchantInterface/Purchase";
    $merchantID = "AVOCADO1";
    $correlationID = "17bcc8d4-bf38-4afa-8791-54830b062788";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
            "Authorization: Bearer ".getToken(),
            "Accept: application/json",
            "Content-Type: application/json",
            "CorrelationID:" . $correlationID,
            "MerchantID:" . $merchantID,
    );
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // should be removed after connecting to the server
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $data = array(
            "MessageTrxID"=>"XXXXXXXXSMDREHAM",
            "MerchantID"=>"AVOCADO1",
            "RAliasType"=>"MOBL",
            "RAliasValue"=>"00962789001121",
            "Amount"=>2.50
    );


    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

    $responseData = curl_exec($curl);
    if( ! $responseData = curl_exec($curl) )
    {
      trigger_error(curl_error($curl));
    }
    curl_close($curl);
    return json_decode($responseData,true);
  }
}