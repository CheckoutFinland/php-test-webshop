<?php

require_once 'config/configuration.php';

use Checkout\API;

// Initialize API
$checkoutApi = new API(MERCHANT_ID, MERCHANT_SECRET);

// Add card form API specific parameters (general authentication parameters are created on getSignedAddCardParameters)
$formParameters = [
    'checkout-redirect-success-url' => CO_SHOP_URL . '/token-add-card-return.php',
    'checkout-redirect-cancel-url' => CO_SHOP_URL . '/token-add-card-return.php',

    // language is not taken into signature calculation thus not prefixed sith 'checkout-'
    'language' => 'EN'
];

// getSignedAddCardParameters generates auth parameters and calculates signature
$signedAddCardParameters = $checkoutApi->getSignedAddCardParameters($formParameters);

$pageMainHeader = 'Call add card form';
require 'templates/tpl-token-add-card.php';
