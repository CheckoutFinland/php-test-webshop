<?php

namespace Helpers;

/**
 * Generate Finnish bank reference
 *
 * @return string
 */
function finnishBankReference()
{
    $ref = strval(rand(100000, 1000000000));
    $weight = [7, 3, 1];
    $sum = 0;
    for ($i = strlen($ref) - 1, $j = 0; $i >= 0; $i--, $j++) {
        $sum += (int) $ref[$i] * (int) $weight[$j % 3];
    }
    $checsum = (10 - ($sum % 10)) % 10;
    return $ref . $checsum;
}

/**
 * Generates a payload for creating a payment
 *
 * https://checkoutfinland.github.io/psp-api/#/?id=create-payment
 *
 * @return object
 */
function generatePaymentPayload()
{
    $payload = (object) [
        'stamp' => str_replace('.', 'TS', microtime(true)),
        'reference' => finnishBankReference(),
        'amount' => 0,
        'currency' => 'EUR',
        'language' => 'EN',
        'items' => [
            [
                'unitPrice' => random_int(5, 50) * 100 + 99,
                'units' => 1,
                'vatPercentage' => 24,
                'productCode' => '#kissa123',
                'deliveryDate' => '2018-10-10'
            ],
            [
                'unitPrice' => random_int(5, 50) * 100 + 99,
                'units' => 1,
                'vatPercentage' => 24,
                'productCode' => '#koira234',
                'deliveryDate' => '2018-10-10'
            ]
        ],
        'customer' => [
            'firstName' => 'Maija',
            'lastName' => 'Mallikas',
            'email' => 'maija.mallikas@example.com',
            'phone' => '0401231231'
        ],
        'redirectUrls' => [
            'success' => CO_SHOP_URL . '/payment-return.php',
            'cancel' => CO_SHOP_URL . '/payment-return.php'
        ]
    ];

    foreach ($payload->items as $item) {
        $payload->amount += $item['units'] * $item['unitPrice'];
    }

    return $payload;
}

/**
 * Generates a payload for creating a shop in shop payment
 *
 * https://checkoutfinland.github.io/psp-api/#/?id=create-payment
 *
 * @param $aggregeteId
 * @param $sisMerchantId
 * @return object
 */
function generateSiSPaymentPayload($sisMerchantId)
{
    $payload = (object) [
        'stamp' => str_replace('.', 'TS', microtime(true)),
        'reference' => finnishBankReference(),
        'amount' => 0,
        'currency' => 'EUR',
        'language' => 'FI',
        'items' => [
            [
                'unitPrice' => random_int(5, 50) * 100 + 99,
                'units' => 1,
                'vatPercentage' => 24,
                'productCode' => '#kissa123',
                'deliveryDate' => '2018-10-10',
                'reference' => 'kissa_ref',
                'merchant' => '' . $sisMerchantId,
                'stamp' => str_replace('.', 'a', microtime(true)),
            ],
            [
                'unitPrice' => random_int(5, 50) * 100 + 99,
                'units' => 1,
                'vatPercentage' => 24,
                'productCode' => '#koira234',
                'deliveryDate' => '2018-10-10',
                'reference' => 'koira_ref',
                'merchant' => '' . $sisMerchantId,
                'stamp' => str_replace('.', 'b', microtime(true)),
            ]
        ],
        'customer' => [
            'firstName' => 'Maija',
            'lastName' => 'Mallikas',
            'email' => 'maija.mallikas@example.com',
            'phone' => '0401231231'
        ],
        'redirectUrls' => [
            'success' => CO_SHOP_URL . '/payment-return.php',
            'cancel' => CO_SHOP_URL . '/payment-return.php'
        ]
    ];

    foreach ($payload->items as $item) {
        $payload->amount += $item['units'] * $item['unitPrice'];
    }

    return $payload;
}

/**
 * Prints out parameter from
 *
 * @param $parameterName
 * @return string
 */
function printGetParameter($parameterName)
{
    if (isset($_GET[$parameterName])) {
        return '<div class="alert alert-light" role="alert">'
            . $parameterName . ': ' . $_GET[$parameterName] . '</div>';
    } else {
        return '<div class="alert alert-warning" role="alert">'
            . 'Missing \'' . $parameterName . '\' parameter</div>';
    }
}

/**
 * Helper to print form fields
 *
 * @param string $formId
 * @param string $id
 * @param string $name
 * @param string $value
 * @param null $description
 * @return string
 */
function formGroup($formId = '', $id = '', $name = '', $value = '', $description = null, $readonly = false)
{
    return '<div class="form-group">'
        . '<label for="' . $id . '">' . $name . '</label>'
        . '<input form="' . $formId . '" type="text" class="form-control" 
                  name="' . $name . '" id="' . $id . '" value="' . $value . '" ' . ($readonly ? 'readonly' : '') . '/>'
        . ($description ? '<small class="form-text text-muted">' . $description . '</small>' : '' )
        . '</div>';
}

/**
 * Helper to print out menu list items
 *
 * @param $list
 * @param $header
 * @param $SelectedTransactionId
 */
function createDropDownList($list, $header, $SelectedTransactionId)
{
    $html = '';

    if (isset($list) && sizeof($list)) {
        $html .= '<h6 class="dropdown-header">' . $header . '</h6>';
        foreach ($list as $txId => $txData) {
            $html .= '<a class="dropdown-item ' . ($txId === $SelectedTransactionId ? 'active' : '') . '"
            href="/payment-status.php?checkout-transaction-id=' . $txId . '">' . $txId . '</a>';
        }
    }

    return $html;
}

/**
 * Helper class to ease showing test credit cards
 *
 * Class CreditCard
 * @package Helpers
 */
class CreditCard
{
    public $tokenization;
    public $payment;
    public $cardNumber;
    public $expiry;
    public $CVC;
    public $tokenizationId;
    public $token;

    public function __construct(
        $tokenizationId,
        $token,
        $tokenization,
        $payment,
        $cardNumber,
        $expiry,
        $CVC,
        $description
    ) {
        $this->tokenization   = $tokenization;
        $this->payment        = $payment;
        $this->cardNumber     = $cardNumber;
        $this->expiry         = $expiry;
        $this->CVC            = $CVC;
        $this->description    = $description;
        $this->tokenizationId = $tokenizationId;
        $this->token          = $token;
    }
}

/**
 * Helper class to ease showing test credit cards
 *
 * Class CreditCards
 * @package Helpers
 */
class CreditCards
{
    private $creditCards;

    public function __construct()
    {
        $this->creditCards = array();

        array_push(
            $this->creditCards,
            new CreditCard(
                '686bd1da-cc39-4f7e-9e64-24ae3403d19a',
                '205ab994-a1a6-406d-87f4-38b3dc45051d',
                'OK',
                'OK',
                '4153 0139 9970 0313',
                '11/2023',
                '313',
                'Successful 3D Secure. 3DS form password "secret".'
            )
        );
        array_push(
            $this->creditCards,
            new CreditCard(
                '5721f2e1-5873-4ad2-a5cb-0ca1ab08a742',
                '77d61296-bb55-4f80-a5db-f0ade34568cc',
                'OK',
                'OK',
                '4153 0139 9970 0321',
                '11/2023',
                '321',
                'Successful 3D Secure. 3DS form will be automatically completed.'
            )
        );
        array_push(
            $this->creditCards,
            new CreditCard(
                '0c1d8b9d-204b-4999-9f84-fbbc0498b84f',
                'f6cbf6c2-a157-4f48-8b5b-fc9d9e211120',
                'OK',
                'OK',
                '4153 0139 9970 0339',
                '11/2023',
                '339',
                '3D Secure attempt. 3DS will be automatically attempted.'
            )
        );
        array_push(
            $this->creditCards,
            new CreditCard(
                '31343be0-2b0b-4cd1-921e-1888629e8d6e',
                'b7d492a1-5ded-489b-add7-65226832a3f7',
                '(OK)',
                '(OK)',
                '4153 0139 9970 0347',
                '11/2023',
                '347',
                '3D Secure fails. The "cardholder_authentication" response parameter will be "no". 
                It is at discretion of the merchant to accept or reject unauthentication transactions. 
                If the merchant decides to decline the payment,
                the transaction should be reverted.'
            )
        );
        array_push(
            $this->creditCards,
            new CreditCard(
                'c6f78db8-7bb3-4d4a-ad7b-da7e4d4ff965',
                'f4650972-c0cd-4dab-8f16-3ecf26439755',
                'OK',
                'FAIL',
                '4153 0139 9970 0354',
                '11/2023',
                '354',
                'Successful 3D Secure. 3DS form password "secret". Insufficient funds in the test bank account.'
            )
        );
        array_push(
            $this->creditCards,
            new CreditCard(
                'b2caa14b-f6ea-4ea9-a972-d463c8e7021c',
                'e3c4b2ea-7e84-47d7-a78b-5ac7e1012199',
                'OK',
                'OK',
                '4153 0139 9970 1162',
                '11/2023',
                '162',
                'with 3DS, Soft decline when charging saved card using Customer Initiated Transaction (requires 3DS). 
                3DS form password "secret".'
            )
        );
        array_push(
            $this->creditCards,
            new CreditCard(
                'b0911d7f-e87f-4a43-b015-a399087ef10c',
                '28007bdb-5865-4fdd-8c66-c7fe342a17e1',
                'OK',
                'OK',
                '4153 0139 9970 1170',
                '11/2023',
                '170',
                'with 3DS, Soft decline when charging saved card using Customer Initiated Transaction (requires 3DS). 
                3DS form will be automatically completed.'
            )
        );
        array_push(
            $this->creditCards,
            new CreditCard(
                'b336baa3-5c8f-44f2-adbc-476113afd6c5',
                '72535e65-092d-4a19-9775-b7cb5ac8e6d0',
                'OK',
                'OK',
                '4153 0139 9970 0024',
                '11/2023',
                '024',
                'Non-EU - "one leg out" card, not enrolled to 3DS. 
                The "cardholder_authentication" response parameter will be "attempted".'
            )
        );
        array_push(
            $this->creditCards,
            new CreditCard(
                '564b790d-c685-4ba4-875f-24ec361a16bd',
                '66d53d1f-6044-4fa6-9997-5d3544ec5632',
                'OK',
                'FAIL',
                '4153 0139 9970 0156',
                '11/2023',
                '156',
                'Non-EU - "one leg out" card, not enrolled to 3DS. Insufficient funds in the test bank account.'
            )
        );
    }

    /**
     * Print simple html table
     *
     * @return string
     */
    public function getTable()
    {
        $html = '<table class="table">
            <thead>
            <tr>
                <th>Tokenization<br />Payment</th>
                <th>Tokenization Id<br />Payment token</th>
                <th>Card number<br />Expiry, CVC</th>
                <th>Description<br />&nbsp;</th>
                <th>Actions<br />&nbsp;</th>
            </tr>
            </thead>
            <tbody>';

        foreach ($this->creditCards as $creditCard) {
            $html .= "<tr>
                <td>
                    {$creditCard->tokenization}<br />
                    {$creditCard->payment}
                </td>
                <td>
                    " . str_replace('-', '&#8209;', $creditCard->tokenizationId) . "<br />
                    " . str_replace('-', '&#8209;', $creditCard->token) . "
                </td>
                <td>
                    <b>" . str_replace(' ', '&nbsp;', $creditCard->cardNumber) . "<br />
                    {$creditCard->expiry}, {$creditCard->CVC}</b>
                </td>
                <td>
                    {$creditCard->description}
                <td>
                    <a href='/credit-card-tokenization.php?checkout-tokenization-id={$creditCard->tokenizationId}'>
                        Tokenization
                    </a>
                    
                    <a href='/token-payment.php?type=cit&token={$creditCard->token}'>
                        CIT&nbsp;payment
                    </a>
                    <a href='/token-payment.php?type=mit&token={$creditCard->token}'>
                        MIT&nbsp;payment
                    </a>
                    <a href='/token-payment.php?authorization-hold=true&type=cit&token={$creditCard->token}'>
                        CIT&nbsp;auth.&nbsp;hold
                    </a>
                    <a href='/token-payment.php?authorization-hold=true&type=mit&token={$creditCard->token}'>
                        MIT&nbsp;auth.&nbsp;hold
                    </a>
                </td>
                
            </tr>";
        }
        $html .= '</tbody></table>';

        return $html;
    }
}

/**
 * Add transaction to transaction list (Session)
 *
 * @param $transactionId
 */
function addTransaction($transactionId)
{
}
