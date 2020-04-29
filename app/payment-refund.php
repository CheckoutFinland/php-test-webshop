<?php

require_once 'config/configuration.php';
require_once 'src/helpers.php';

use Checkout\API;

if (isset($_GET['checkout-transaction-id'])) {
    $checkoutApi = new API(MERCHANT_ID, MERCHANT_SECRET);

    // Create refund payload
    $payload = (object)[
        'amount' => (int)$_GET['refund-amount'],
        'refundStamp' => str_replace('.', 'TS', microtime(true)),
        'refundReference' => \Helpers\finnishBankReference(),
        'email' => 'example@example.com',
        'callbackUrls' => [
            'success' => CO_SHOP_URL . '/refund-return.php',
            'cancel' => CO_SHOP_URL . '/refund-return.php'
        ]
    ];

    $response = $checkoutApi->refund($_GET['checkout-transaction-id'], $payload);

    // Store transaction in session is set
    if (isset($response->transactionId)) {
        $_SESSION['transactions']['refund'][$response->transactionId] = $payload;
    }
}

// page header
$pageMainHeader = 'Payment Status';
require 'templates/tpl-payment-status.php';
