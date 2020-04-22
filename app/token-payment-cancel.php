<?php

require_once 'config/configuration.php';
require_once 'src/helpers.php';

use Checkout\API;

if (isset($_GET['checkout-transaction-id'])) {
    $checkoutApi = new API(MERCHANT_ID, MERCHANT_SECRET);
    $response = $checkoutApi->tokenPaymentCancel($_GET['checkout-transaction-id']);
} else {
    $alert = ['message' => 'No transaction id given...', 'type' => 'warning'];
}

$isAuthorizationHold = true;

$pageMainHeader = 'Cancel token payment';
require 'templates/tpl_token_payment.php';
