<?php

namespace tobyokeke\flutterwave;

use GuzzleHttp\Client;

class Flutterwave
{
    private $accountVerificationUrlV3 = 'accounts/resolve';
    private $bvnVerificationUrl = 'kyc/bvns/';
    private $transferUrl = 'transfers/';
    private $virtualAccountUrl = 'virtual-account-numbers';
    private $chargeVerificationUrl = "transactions/:id/verify";
    private $transferConfirmationUrl = "transfers/";
    private $tokenizedChargeUrl = "tokenized-charges/";
    private $walletBalanceUrl = "balances/";
    private $transactionsUrl = "transactions";
    private $chargeBackUrl = "chargebacks";
    private $baseUrl;
    private $env;
    private $client;
    private $headers;
    private $secretKey;
    private $publicKey;

    public function __construct()
    {
        $devBaseUrl  = "https://ravesandboxapi.flutterwave.com/v3/";
        $prodBaseUrl = "https://api.flutterwave.com/v3/";

        $env = env('FLUTTERWAVE_ENV');

        if ($env == 'dev') {
            $this->env     = $env;
            $this->baseUrl = $devBaseUrl;
        } else {
            $this->env     = "prod";
            $this->baseUrl = $prodBaseUrl;
        }

        $this->secretKey = env('FLUTTERWAVE_SECRET_KEY');
        $this->publicKey = env('FLUTTERWAVE_PUBLIC_KEY');

        $this->client  = new Client();
        $this->headers = [
            "Authorization" => "Bearer $this->secretKey",
            "Content-Type"  => "application/json"
        ];
    }


    private function get(string $url, array $data = null)
    {

        $response = $this->client->request('GET', $this->baseUrl.$url, [
            'query'   => $data,
            'headers' => $this->headers,
        ]);

        return json_decode($response->getBody()->getContents());

    }


    public function transactions($from, $to, $page = 1, $status = "successful", $currency = "NGN")
    {
        $url = $this->transactionsUrl;

        $data = [
            "page"     => $page,
            "status"   => $status,
            "currency" => $currency,
            "from"     => $from,
            "to"       => $to
        ];


        return $this->get($url, $data);
    }

}
