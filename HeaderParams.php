<?php

class headerParams {
  private $userID;
  private $password;
  private $merchantID;
  private $correlationID;
  private $securityPassword;

  public function __construct() {
    $this->userID           = "UWallet";
    $this->password         = "wyxthKzbGn";
    $this->merchantID       = "AVOCADO1";
    $this->correlationID    = "17bcc8d4-bf38-4afa-8791-54830b062788";
    $this->securityPassword = "}tqNFE9Hr5Q87}sJ";
  }


  /**
   * @return mixed
   */
  public function getUserID() {
    return $this->userID;
  }

  /**
   * @param   mixed  $userID
   */
  public function setUserID($userID): void {
    $this->userID = $userID;
  }

  /**
   * @return mixed
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @param   mixed  $password
   */
  public function setPassword($password): void {
    $this->password = $password;
  }

  /**
   * @return mixed
   */
  public function getMerchantID() {
    return $this->merchantID;
  }

  /**
   * @param   mixed  $merchantID
   */
  public function setMerchantID($merchantID): void {
    $this->merchantID = $merchantID;
  }

  /**
   * @return mixed
   */
  public function getCorrelationID() {
    return $this->correlationID;
  }

  /**
   * @param   mixed  $correlationID
   */
  public function setCorrelationID($correlationID): void {
    $this->correlationID = $correlationID;
  }

  /**
   * @return string
   */
  public function getSecurityPassword(): string {
    return $this->securityPassword;
  }

  /**
   * @param   string  $securityPassword
   */
  public function setSecurityPassword(string $securityPassword): void {
    $this->securityPassword = $securityPassword;
  }


}

?>
