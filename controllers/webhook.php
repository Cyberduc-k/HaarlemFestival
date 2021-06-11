<?php
use Mollie\Api\MollieApiClient;
require_once __DIR__.'/../libs/Mollie/src/MollieApiClient.php';
require_once __DIR__.'/../libs/Mollie/functions.php';


$mollie = new MollieApiClient();
$mollie->setApiKey("test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8");



require __DIR__.'/../webhook.php';