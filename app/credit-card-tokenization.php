<?php

require_once 'config/configuration.php';
require_once 'src/helpers.php';

use Checkout\API;

$tokenizationId = null;

if (isset($_GET['checkout-tokenization-id'])) {
    $tokenizationId = $_GET['checkout-tokenization-id'];
    $checkoutApi = new API(MERCHANT_ID, MERCHANT_SECRET);
    $response = $checkoutApi->tokenization($tokenizationId);
}

$pageMainHeader = 'Get card token';
require 'templates/tpl-creditcard-tokenization.php';
