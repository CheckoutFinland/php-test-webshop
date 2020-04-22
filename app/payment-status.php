<?php

require_once 'config/configuration.php';
require_once 'src/helpers.php';

use Checkout\API;

if (isset($_GET['checkout-transaction-id'])) {
    // check if sis payment
    $isSiSPayment = isset($_SESSION['transactions']['sisPayment'][($_GET['checkout-transaction-id'])]);
    $isAuthorizationHold = isset($_SESSION['transactions']['authorizationHold'][($_GET['checkout-transaction-id'])]);

    // Initialize checkout API
    $checkoutApi = new API(
        $isSiSPayment ? SHOP_IN_SHOP_AGGREGATE_ID : MERCHANT_ID,
        $isSiSPayment ? SHOP_IN_SHOP_AGGREGATE_SECRET : MERCHANT_SECRET,
    );
    $response = $checkoutApi->paymentStatus($_GET['checkout-transaction-id']);
}

$pageMainHeader = 'Payment Status';
require 'templates/tpl-payment-status.php';
