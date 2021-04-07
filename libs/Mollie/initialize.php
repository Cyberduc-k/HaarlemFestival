<?php
namespace _PhpScoperd8ff184be637;

use Mollie\Api\MollieApiClient;
use function error_reporting;
use function ini_set;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(\E_ALL);

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/functions.php";

$mollie = new MollieApiClient();
$mollie->setApiKey("test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8");
