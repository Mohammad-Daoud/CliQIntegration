<?php
include("HeaderParams.php");

class TokenHandler {
  private function tokenRequest() {
    $url       = "https://testapi.uwallet.jo/A2AMerchantInterface/GetToken";
    $allParams = new headerParams();
    $curl      = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "CorrelationID:" . $allParams->getCorrelationID(),
            "MerchantID:" . $allParams->getMerchantID(),
            "UserID:" . $allParams->getUserID(),
            "Password:" . $allParams->getPassword()
    );
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // should be removed after connecting to the server
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $data = array("SecurityKey" => $allParams->getSecurityPassword());

    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

    $responseData = curl_exec($curl);
    if ( ! $responseData = curl_exec($curl)) {
      trigger_error(curl_error($curl));
    }
    curl_close($curl);

    return json_decode($responseData, TRUE);
  }

  public function TokenHandler() {
    return $this->tokenRequest()['TokenInfo']['Token'];
  }
}

?>