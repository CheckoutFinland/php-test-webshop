<?php

/**
 * This is a very simple Checkout payment API class to create and manage payments
 * Should not be used in production environments as such but as example instead
 *
 * PHP version 5
 *
 * @category CheckoutPSPAPI
 * @package  CheckooutAPI
 * @author   Checkout <api@checkout.fi>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @version  GIT: <git_id>
 * @link     https://checkoutfinland.github.io/psp-api
 */

namespace Checkout;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Ramsey\Uuid\Uuid;
use Throwable;

/**
 * Checkout PSP API
 *
 * @category CheckoutPSPAPI
 * @package  CheckooutAPI
 * @author   Checkout <api@checkout.fi>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://checkoutfinland.github.io/psp-api
 */
class API
{
    protected $secret;
    protected $merchant;

    /**
     * Payment constructor.
     *
     * @param $merchant string Merchant id
     * @param $secret   string Merchant secret
     */
    public function __construct($merchant, $secret)
    {
        $this->merchant = $merchant;
        $this->secret = $secret;
    }

    /**
     * Calculate Checkout signature for the request
     *
     * @param $params array HTTP headers or query string
     * @param $body   string HTTP request body, empty string for GET requests
     *
     * @return string SHA-256 HMAC
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=authentication
     */
    public function calculateSignature($params, $body = '')
    {
        // Keep only checkout- params, more relevant for response validation. Filter query
        // string parameters the same way - the signature includes only checkout- values.
        $includedKeys = array_filter(
            array_keys($params),
            function ($key) {
                return preg_match('/^checkout-/', $key);
            }
        );

        // Keys must be sorted alphabetically
        sort($includedKeys, SORT_STRING);

        $hmacPayload
            = array_map(
                function ($key) use ($params) {
                    return join(':', [ $key, $params[$key] ]);
                },
                $includedKeys
            );

        array_push($hmacPayload, $body);

        return hash_hmac(ALGORITHM, join("\n", $hmacPayload), $this->secret);
    }

    /**
     * Get signed header parameters as an array
     *
     * @param array $additionalHeaders
     * @param string $method HTTP request method GET/POST
     * @param string $payload Payment payload
     * @return array Headers as an array including signature
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=authentication
     */
    private function getSignedHeaders($additionalHeaders = [], $method = 'post', $payload = '')
    {

        $isPostRequest = strtolower($method) === 'post';

        // common headers needed for authentication
        $headers = [
            'checkout-account' => $this->merchant,
            'checkout-method' => $isPostRequest ? 'POST' : 'GET',
            'checkout-algorithm' => ALGORITHM,
            'checkout-timestamp' => date('c'),
            'checkout-nonce' => Uuid::uuid4()->toString(),
            'content-type' => 'application/json; charset=utf8'
        ];

        // set possible additional headers (they will overwrite basic values if overlapping)
        $headers = array_merge($headers, $additionalHeaders);

        // calculate and add signature to headers
        $headers['signature'] = $this->calculateSignature($headers, ($isPostRequest ? $payload : ''));

        return $headers;
    }

    /**
     * Get signed Parameter for GET requests
     *
     * @param array $parameters Parameters to be signed
     * @return array Array including calculated signature
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=authentication
     */
    public function getSignedAddCardParameters($parameters)
    {
        // signature for post parameters can be calculated the same way as for headers
        return $this->getSignedHeaders($parameters);
    }

    /**
     * Request client using guzzle
     *
     * @param  string $method HTTP request method GET/POST
     * @param  string $url API url to be called
     * @param  string $payload Request payload
     * @param  array  $headers Request specific headers (mandatory headers are added automatically)
     * @return mixed JSON decoded response
     *
     * @link http://docs.guzzlephp.org/en/stable/
     */
    protected function request($method, $url, $payload = '', $headers = [])
    {
        try {
            $isGetRequest = strtolower($method) === 'get';

            $client = new Client(
                [
                'headers' => $this->getSignedHeaders($headers, $method, json_encode($payload))
                ]
            );

            $response = null;

            try {
                if ($isGetRequest) {
                    $response = $client->get($url);
                } else {
                    $response = $client->post($url, [ 'body' => json_encode($payload) ]);
                }
            } catch (ClientException $e) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse();
                } else {
                    die('hmm, why here');
                }
            } catch (ServerException $e) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse();
                } else {
                    die('hmm, why here');
                }
            }

            $responseBody = $response->getBody()->getContents();
        } catch (Throwable $e) {
            $responseBody = '{"status": "error",  "message": "HTTP Request to ' . $url . ' failed"}';
        }

        return ($responseJSON = json_decode($responseBody))
            ? $responseJSON
            : json_decode(
                '
                {"status": 
                    "error",
                "message": 
                    "Failed to parse response from ' . $url . ', json_last_error_msg = ' . json_last_error_msg() . '"
                }'
            );
    }

    /**
     * Create a payment
     *
     * @param object $payload Payment payload
     *                        https://checkoutfinland.github.io/psp-api/#/examples?id=create
     * @return bool|string
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=payments
     */
    public function createPayment($payload)
    {
        $url = CO_API_URL . '/payments';
        return $this->request('post', $url, $payload);
    }

    /**
     * Get payment (transaction) status
     *
     * @param string $transactionId Transaction id
     * @return bool|string
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=get
     */
    public function paymentStatus($transactionId)
    {
        $url = CO_API_URL . '/payments/' . $transactionId;
        // transaction id needs to be set in headers as well
        $headers = ['checkout-transaction-id' => $transactionId];
        return $this->request('get', $url, '', $headers);
    }

    /**
     * Create a refund
     *
     * @param string $transactionId Transaction id
     * @param object $payload Refund payload
     *                        https://checkoutfinland.github.io/psp-api/#/examples?id=refund
     * @return bool|string
     *
     * $link https://checkoutfinland.github.io/psp-api/#/?id=refund
     */
    public function refund($transactionId, $payload)
    {
        $url = CO_API_URL . '/payments/' . $transactionId . '/refund';

        // transaction id needs to be set in headers as well
        $headers = ['checkout-transaction-id' => $transactionId];
        return $this->request('post', $url, $payload, $headers);
    }

    /**
     * Tokenize a credit card using its tokenization id (id is fetched with add card form)
     *
     * @param string $tokenizationId Credit card tokenization id fetched with add card form
     * @return bool|string
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=adding-tokenizing-cards
     */
    public function tokenization($tokenizationId)
    {
        $url = CO_API_URL . '/tokenization/' . $tokenizationId;
        // tokenization id needs to be set in headers as well
        $headers = ['checkout-tokenization-id' => $tokenizationId];
        return $this->request('post', $url, '', $headers);
    }

    /**
     * Create a merchant initiated token payment
     *
     * @param object $payload Token payment payload
     *                        https://checkoutfinland.github.io/psp-api/#/examples?id=create
     * @return bool|string
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=create-authorization-hold-or-charge
     */
    public function createTokenMitCharge($payload)
    {
        $url = CO_API_URL . '/payments/token/mit/charge';
        return $this->request('post', $url, $payload);
    }

    /**
     * Create a merchant initiated authorization hold
     *
     * @param object $payload Token payment payload
     *                        https://checkoutfinland.github.io/psp-api/#/examples?id=create
     * @return bool|string
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=create-authorization-hold-or-charge
     */
    public function createTokenMitAuthorizationHold($payload)
    {
        $url = CO_API_URL . '/payments/token/mit/authorization-hold';
        return $this->request('post', $url, $payload);
    }

    /**
     * Create a customer initiated charge
     *
     * @param object $payload Token payment payload
     *                        https://checkoutfinland.github.io/psp-api/#/examples?id=create
     * @return bool|string
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=create-authorization-hold-or-charge
     */
    public function createTokenCitCharge($payload)
    {
        $url = CO_API_URL . '/payments/token/cit/charge';
        return $this->request('post', $url, $payload);
    }

    /**
     * * Create a customer initiated authorization hold
     *
     * @param object $payload Token payment payload
     *                        https://checkoutfinland.github.io/psp-api/#/examples?id=create
     * @return bool|string
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=create-authorization-hold-or-charge
     */
    public function createTokenCitAuthorizationHold($payload)
    {
        $url = CO_API_URL . '/payments/token/cit/authorization-hold';
        return $this->request('post', $url, $payload);
    }

    /**
     * Commit authorization hold
     *
     * @param string $transactionId
     * @param object $payload Token payment payload to be committed
     *                        https://checkoutfinland.github.io/psp-api/#/examples?id=create
     * @return mixed
     *
     * @link https://checkoutfinland.github.io/psp-api/#/?id=commit-authorization-hold
     */
    public function tokenPaymentCommit($transactionId, $payload)
    {
        $url = CO_API_URL . '/payments/' . $transactionId . '/token/commit';
        // transaction id needs to be set in headers as well
        $headers = ['checkout-transaction-id' => $transactionId];
        return $this->request('post', $url, $payload, $headers);
    }

    /**
     * Revert authorization hold
     *
     * https://checkoutfinland.github.io/psp-api/#/?id=revert-authorization-hold
     *
     * @param string $transactionId
     * @return mixed
     */
    public function tokenPaymentCancel($transactionId)
    {
        $url = CO_API_URL . '/payments/' . $transactionId . '/token/revert';
        // transaction id needs to be set in headers as well
        $headers = ['checkout-transaction-id' => $transactionId];
        return $this->request('post', $url, '', $headers);
    }
}
