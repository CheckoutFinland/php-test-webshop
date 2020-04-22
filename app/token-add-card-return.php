<?php

require_once 'config/configuration.php';
require_once 'src/helpers.php';

use Checkout\API;

// Always check return url parameters
if (isset($_GET['signature'])) {
    $checkoutApi = new API(MERCHANT_ID, MERCHANT_SECRET);
    $signature = $checkoutApi->calculateSignature($_GET);

    if ($_GET['signature'] === $signature) {
        $alert = ['message' => 'Parameters signed ok', 'type' => 'success'];
    } else {
        $alert = ['message' => 'Signature mismatch', 'type' => 'danger'];
    }
} else {
    $alert = ['message' => 'Signature missing', 'type' => 'danger'];
}

$pageMainHeader = 'Add a card return';
require 'templates/tpl-add-card-return.php';
