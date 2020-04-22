<?php

require_once 'config/configuration.php';

use Checkout\API;

$error = '';

if (isset($_GET['signature'])) {
    // Initiate API with correct credentials for shop in shop/normal merchant
    $checkoutApi = new API(
        $_GET['checkout-account'],
        ($_GET['checkout-account'] === SHOP_IN_SHOP_AGGREGATE_ID) ? SHOP_IN_SHOP_AGGREGATE_SECRET : MERCHANT_SECRET
    );

    // Calculate and compare signature from parameters
    if ($checkoutApi->calculateSignature($_GET) !== $_GET['signature']) {
        $alert = ['message' => 'Signature mismatch', 'type' => 'danger'];
    }
}

$pageMainHeader = 'Payment return';
require 'templates/tpl-payment-return.php';
