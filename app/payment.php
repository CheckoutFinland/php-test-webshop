<?php

require_once 'config/configuration.php';
require_once 'src/helpers.php';

use Checkout\API;

$isSiSPayment = isset($_GET['type']) && strtolower($_GET['type']) === 'sis';

// Initialize checkout API
$checkoutApi = new API(
    $isSiSPayment ? SHOP_IN_SHOP_AGGREGATE_ID : MERCHANT_ID,
    $isSiSPayment ? SHOP_IN_SHOP_AGGREGATE_SECRET : MERCHANT_SECRET,
);

// Get correct payload
$payload = $isSiSPayment
    ? Helpers\generateSiSPaymentPayload(SHOP_IN_SHOP_SUB_MERCHANT_ID)
    : Helpers\generatePaymentPayload();

// Create payment
$response = $checkoutApi->createPayment($payload);

// Just store the transaction id to access it later.. ..like normal store would probably do :)
if (isset($response->transactionId)) {
    $_SESSION['transactions'][($isSiSPayment ? 'sisPayment' : 'normal')][$response->transactionId] = $payload;
}

$pageMainHeader = $isSiSPayment ? 'Shop in shop payment' : 'Normal payment';
require 'templates/tpl-payment.php';
