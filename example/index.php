<?php

require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

$client = new \Couchy\Client();
var_dump($client->createDatabaseIfNotExists('test'));

var_dump($client->listDatabases());
