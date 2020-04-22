<?php

require_once 'config/configuration.php';
require_once 'src/helpers.php';

use Checkout\API;

if (isset($_GET['checkout-transaction-id'])) {
    // GET payload from session as it needs to be included in commit
    $payload = (object)$_SESSION['transactions']['authorizationHold'][$_GET['checkout-transaction-id']];

    $checkoutApi = new API(MERCHANT_ID, MERCHANT_SECRET);
    $response = $checkoutApi->tokenPaymentCommit($_GET['checkout-transaction-id'], $payload);
} else {
    $alert = ['message' => 'No transaction id given...', 'type' => 'warning'];
}

$pageMainHeader = 'Commit token payment';
require 'templates/tpl_token_payment.php';
