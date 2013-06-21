<?php

require_once 'WindowsAzure/WindowsAzure.php';

use WindowsAzure\Blob\Models\BlockList;
use WindowsAzure\Blob\Models\GetBlobOptions;
use WindowsAzure\Common\ServicesBuilder;

class opAzureBlob extends opAzure
{
  private $container;
  private $blobName;

  public function __construct($blobName)
  {
    $this->blobName = $blobName;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->blobName;
  }

  /**
   * @param string $blobName
   */
  public function getContentLength()
  {
    $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($this->getConnectionString());

    $blobProperties = $blobRestProxy->getBlobProperties($this->getContainer()->getName(), $this->getName());

    return $blobProperties->getProperties()->getContentLength();
  }

  /**
   * @param opAzureContainer $container
   */
  public function setContainer(opAzureContainer $container)
  {
    $this->container = $container;
  }

  /**
   * @return opAzureContainer
   */
  public function getContainer()
  {
    return $this->container;
  }

  public function getContent()
  {
    $contentLength = $this->getContentLength();

    $numOfBlocks = ceil($contentLength / self::chunkSize);

    $currentFileIndex = 0;
    return function() {
      $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($this->getConnectionString());

      if ($currentFileIndex > $contentLength)
      {
        return false;
      }

      $blobOptions = new GetBlobOptions();
      $blobOptions->setRangeStart($currentFileIndex);
      $blobOptions->setRangeEnd($currentFileIndex += self::chunkSize);
      $blob = $blobRestProxy->getBlob($this->getContainer()->getName(), $this->getName(), $blobOptions);

      return $blob->getContentStream();
    };
  }
}
