<?php

require_once 'WindowsAzure/WindowsAzure.php';

use WindowsAzure\Common\ServicesBuilder;

class opAzureContainer extends opAzure
{
  private $containerName;

  public function __construct($containerName)
  {
    $this->containerName = $containerName;
  }

  /**
   * Blob クラスの配列を返す
   *
   * @return array()
   */
  public function getList()
  {
    $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($this->getConnectionString());
    $blobList = $blobRestProxy->listBlobs($this->containerName);

    return $blobList->getBlobs();
  }

  public function get($blobName)
  {
    $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($this->getConnectionString());

    $azureBlob = new opAzureBlob($blobName);
    $azureBlob->setContainer($this);

    return $azureBlob;
  }

  /**
   * @param string $blobName
   * @param string $filepath
   *
   * @return opAzureBlob
   * @throw WindowsAzure\Common\ServiceException
   */
  public function save($blobName, $filepath)
  {
    $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($this->getConnectionString());
    $currentFileIndex = 0;
    $numOfBlocks = ceil(filesize($filepath) / self::chunkSize);

    $blockList = new BlockList();
    for ($blockId = 1; $blockId <= $numOfBlocks; ++$blockId)
    {
      $blockIdHash = md5($blockId);

      $content = file_get_contents($filepath, null, null, $currentFileIndex, self::chunkSize);
      $blobRestProxy->createBlobBlock($this->containerName, $blobName, $blockIdHash, $content);
      $blockList->addLatestEntry($blockIdHash);

      $currentFileIndex += self::chunkSize;
    }
    $blobRestProxy->commitBlobBlocks($this->containerName, $blobName, $blockList);


    $azureBlob = new opAzureBlob($blobName);
    $azureBlob->setContainer($this);

    return $azureBlob;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->containerName;
  }
}
