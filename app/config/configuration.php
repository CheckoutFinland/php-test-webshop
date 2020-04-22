<?php
session_start();

if (isset($_GET['resetWebShop'])) {
    session_destroy();
    header('Location: /index.php');
}

if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

if (!isset($_SESSION['tokenTransactions'])) {
    $_SESSION['tokenTransactions'] = [];
}

// autoload composer stuff
require_once __DIR__ . '../../vendor/autoload.php';

date_default_timezone_set('UTC');

// Checkout API url
const CO_API_URL = 'https://api.checkout.fi';

// This is usually the same as CO_API_URL but on local psp-api development there might be need to change this.
// Another way to get round this to use local psp-api via ngrok etc.
const CO_API_LOCALHOST_URL = CO_API_URL;

// Url to this test webshop
// While testing token payments (with 3DS) ngrok etc is required as gateway does not allow redirects to non https urls
const CO_SHOP_URL = 'http://localhost';

// Algorithm to be used on signing the messages
const ALGORITHM = 'sha256';

// Test merchant data for normal payments
const MERCHANT_SECRET = 'SAIPPUAKAUPPIAS';
const MERCHANT_ID = '375917';

// Shop in shop aggregate and merchant
const SHOP_IN_SHOP_AGGREGATE_ID = '695861';
const SHOP_IN_SHOP_AGGREGATE_SECRET = 'MONISAIPPUAKAUPPIAS';
const SHOP_IN_SHOP_SUB_MERCHANT_ID = '695874';

// default token to use on token payments if not set
const DEFAULT_TOKEN = '205ab994-a1a6-406d-87f4-38b3dc45051d';
