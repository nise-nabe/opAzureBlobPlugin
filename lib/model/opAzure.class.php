<?php

class opAzure
{
  const chunkSize = 4194304; //4 * (1024 * 1024)

  /**
   * @return string
   */
  public function getConnectionString()
  {
    $protocol = sfConfig::get('app_opAzureBlob_protocol');
    $accountName = sfConfig::get('app_opAzureBlob_accountName');
    $accountKey = sfConfig::get('app_opAzureBlob_accountKey');

    return $this->createConnectionString($protocol, $accountName, $accountKey);
  }

  /**
   * @param string $protocol
   * @param string $accountName
   * @param string $accountKey
   *
   * @return string
   */
  private function createConnectionString($protocol, $accountName, $accountKey)
  {
    $endpointProtocolString = 'DefaultEndpointsProtocol='.$protocol;
    $accountNameString = 'AccountName='.$accountName;
    $accountKeyString = 'AccountKey='.$accountKey;

    return $endpointProtocolString.';'.$accountNameString.';'.$accountKeyString;
  }
}
