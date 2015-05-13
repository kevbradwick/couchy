<?php

require_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

$client = new \Couchy\Client();
$database = $client->createDatabaseIfNotExists('test');
