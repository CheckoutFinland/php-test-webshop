<?php

require_once 'config/configuration.php';
require_once 'src/helpers.php';

use Checkout\API;

// Generate test payment payload
$payload = Helpers\generatePaymentPayload();

// Set token to payload. if token not set use default all ok token
$payload->token = isset($_GET['token'])
    ? $_GET['token']
    : DEFAULT_TOKEN;

// Initialize checkout API
$checkoutApi = new API(MERCHANT_ID, MERCHANT_SECRET);

// Create authorization hold or direct charge
$isAuthorizationHold = isset($_GET['authorization-hold']);

// Customer or merchant initialized payment
$isMitPayment = (isset($_GET['type']) && strtolower($_GET['type']) === 'mit');

if ($isAuthorizationHold) {
    if ($isMitPayment) {
        $pageMainHeader = 'Create a Merchant Initiated (MIT) authorization hold';
        $response = $checkoutApi->createTokenMitAuthorizationHold($payload);
    } else {
        $pageMainHeader = 'Create a Customer Initiated (CIT) authorization hold';
        $response = $checkoutApi->createTokenCitAuthorizationHold($payload);
    }
} elseif ($isMitPayment) {
    $pageMainHeader = 'Create a Merchant Initiated (MIT) token payment';
    $response = $checkoutApi->createTokenMitCharge($payload);
} else {
    $pageMainHeader = 'Create a Customer Initiated (CIT) token payment';
    $response = $checkoutApi->createTokenCitCharge($payload);
}

// simply store payload to commit authorization holds later with the same payload.
if (isset($response->transactionId)) {
    $_SESSION['transactions'][($isAuthorizationHold ? 'authorizationHold' : 'charge')][$response->transactionId]
        = $payload;
}

require 'templates/tpl_token_payment.php';
