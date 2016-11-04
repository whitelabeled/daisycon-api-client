<?php
namespace whitelabeled\DaisyconApi;

use DateTime;
use Httpful\Request;

class DaisyconClient {
    private $username;
    private $password;
    private $publisherId;
    private $token;

    protected $endpoint = 'https://services.daisycon.com';
    protected $itemsPerPage = 1000;

    public $mediaIds = [];

    public function __construct($username, $password, $publisherId) {
        $this->username = $username;
        $this->password = $password;
        $this->publisherId = $publisherId;

        $this->generateToken();
    }

    private function generateToken() {
        $request = Request::post($this->endpoint . '/authenticate')
            ->sendsJson()
            ->body(['username' => $this->username, 'password' => $this->password]);

        $response = $request->send();

        if ($response->hasErrors()) {
            $this->token = null;
            throw new \Exception('Auth failure');
        }

        $this->token = $response->body;
    }

    private function getToken() {
        if ($this->token == null) {
            $this->generateToken();
        }

        return $this->token;
    }

    public function getTransactions(DateTime $startDate, DateTime $endDate = null) {
        $params = [
            'page'                => 1,
            'per_page'            => $this->itemsPerPage,
            'date_modified_start' => $startDate->format('Y-m-d H:i:s'),
        ];

        if ($this->mediaIds != null && count($this->mediaIds) > 0) {
            $params['media_id'] = join(',', $this->mediaIds);
        }

        if ($endDate != null) {
            $params['date_modified_end'] = $endDate->format('Y-m-d H:i:s');
        }

        $query = '?' . http_build_query($params);
        $transactionsData = $this->makeRequest("/publishers/{$this->publisherId}/transactions", $query);

        $transactions = [];

        if ($transactionsData != null) {
            foreach ($transactionsData as $transactionData) {
                foreach ($transactionData->parts as $transPart) {
                    $transaction = Transaction::createFromJson($transactionData, $transPart);
                    $transactions[] = $transaction;
                }
            }
        }

        return $transactions;
    }

    protected function makeRequest($resource, $query = "") {
        $uri = $this->endpoint . $resource;
        echo $uri;

        $request = Request::get($uri . $query)
            ->addHeader('Authorization', 'Bearer ' . $this->getToken())
            ->expectsJson();

        $response = $request->send();

        // Check for errors
        if ($response->hasErrors()) {
        }

        return $response->body;
    }
}
