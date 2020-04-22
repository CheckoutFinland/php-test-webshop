<?php

require_once 'config/configuration.php';
require_once 'src/helpers.php';

use Checkout\API;

// this is propably never called for test merchants

if (isset($_GET['signature']) && isset($_GET['checkout-account'])) {
    // Initiate API with correct credentials for shop in shop/normal merchant
    $checkoutApi = new API(
        $_GET['checkout-account'],
        ($_GET['checkout-account'] === SHOP_IN_SHOP_AGGREGATE_ID) ? SHOP_IN_SHOP_AGGREGATE_SECRET : MERCHANT_SECRET
    );

    // store transaction to session
    $_SESSION['transactions'][$_GET['checkout-transaction-id']] = $_GET;

    // Calculate and compare signature from parameters
    if ($checkoutApi->calculateSignature($_GET) === $_GET['signature']) {
        // signature matched --> we can trust on status
        if ($_GET['checkout-status'] === 'ok') {
            echo 'All ok';
        } else {
            echo 'Transaction failed.. ..do something??';
        }
    } else {
        echo 'signature mismatch';
    }
} else {
    echo 'Missing parameters to calculate signature';
}
