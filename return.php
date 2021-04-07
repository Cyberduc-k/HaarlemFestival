<?php

require_once "libs/Mollie/functions.php";

$status = database_read($_GET["order_id"]);
$mollie = new \Mollie\Api\MollieApiClient();


?>
<html lang="en">
<head>
    <title>Thank you</title>
</head>
<body>
Thank you for yor your purchase.
the current status of your order is <?=$status?>

</body>
</html>
