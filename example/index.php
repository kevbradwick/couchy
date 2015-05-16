<?php

require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

$client = new \Couchy\Client();
$database = $client->createDatabaseIfNotExists('test');
$document = $database->getDocumentById('0a92ef3fd2a8b5e0d6145f5513000a4c');

var_dump($document);
