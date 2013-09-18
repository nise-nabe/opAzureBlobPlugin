opAzureBlobPlugin
=================

Under Construction

Azure の BLOB ストレージに保存するライブラリプラグイン

## Setup

```
$ cd opAzureBlobPlugin
$ cp config/app.yml.sample config/app.yml
$ vim config/app.yml
all:
  opAzureBlob:
    protocol: 'https'
    accountName: 'openpne'
    accountKey: 'secretKey'
```


## Dependencies

WindowsAzure ライブラリ
```
$ pear install pear.windowsazure.com/WindowsAzure-0.3.1
```

see more details [Windows Azure SDK for PHP](https://github.com/WindowsAzure/azure-sdk-for-php/)

## LICENSE

[The BSD 2-Clause License](http://opensource.org/licenses/BSD-2-Clauseh)
