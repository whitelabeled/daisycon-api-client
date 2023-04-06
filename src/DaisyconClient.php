<?php

namespace whitelabeled\DaisyconApi;

use DateTime;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\GenericProvider;
use whitelabeled\DaisyconApi\auth\LoginResponse;

class DaisyconClient
{
    protected $publisherId;
    protected $endpoint = 'https://services.daisycon.com';
    protected $itemsPerPage = 200;

    /**
     * @var boolean Enable revenue share processing
     */
    public $revShareEnabled = false;

    /**
     * @var array Restrict to media ID's
     */
    public $mediaIds = [];

    /**
     * @var GenericProvider
     */
    private $oAuthProvider;

    private $accessToken;

    private $httpClient;


    /**
     * DaisyconClient constructor.
     */
    public function __construct($publisherId, $clientId, $clientSecret, $redirectUri)
    {
        $this->publisherId = $publisherId;

        // Setup oAuth
        $this->oAuthProvider = new GenericProvider(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
                'redirectUri' => $redirectUri,
                'urlAuthorize' => 'https://login.daisycon.com/oauth/authorize',
                'urlAccessToken' => 'https://login.daisycon.com/oauth/access-token',
                'urlResourceOwnerDetails' => '',
                'pkceMethod' => GenericProvider::PKCE_METHOD_S256,
            ]
        );

        $this->httpClient = new Client();
    }

    public function login(): LoginResponse
    {
        $response = new LoginResponse();

        $response->loginUrl = $this->oAuthProvider->getAuthorizationUrl();
        $response->pkceCode = $this->oAuthProvider->getPkceCode();
        $response->state = $this->oAuthProvider->getState();

        return $response;
    }

    public function verifyAuthCode(string $state, string $pkce, string $returnedState, string $returnedCode): string
    {
        if ($state != $returnedState) {
            throw new \Exception('State does not match');
        }

        // Restore the PKCE code before the `getAccessToken()` call.
        $this->oAuthProvider->setPkceCode($pkce);

        // Try to get an access token using the authorization code grant.
        $this->accessToken = $this->oAuthProvider->getAccessToken('authorization_code', [
            'code' => $returnedCode
        ]);

        return $this->accessToken->getRefreshToken();
    }

    public function refreshAccessToken($refreshToken) {
        $this->accessToken = $this->oAuthProvider->getAccessToken('refresh_token', [
            'refresh_token' => $refreshToken,
        ]);

        return $this->accessToken->getRefreshToken();
    }


    /**
     * Get all transactions from $startDate until $endDate.
     *
     * @param DateTime $startDate Start date
     * @param DateTime|null $endDate End date, optional.
     * @param int $page Page, optional. Pagination starts with page=1
     * @return array Transaction objects. Each part of a transaction is returned as a separate Transaction.
     * @throws DaisyconApiException
     */
    public function getTransactions(DateTime $startDate, DateTime $endDate = null, $page = 1)
    {
        $params = [
            'page' => $page,
            'per_page' => $this->itemsPerPage,
            'date_modified_start' => $startDate->format('Y-m-d H:i:s'),
        ];

        if ($this->mediaIds != null && count($this->mediaIds) > 0) {
            $params['media_id'] = join(',', $this->mediaIds);
        }

        if ($endDate != null) {
            $params['date_modified_end'] = $endDate->format('Y-m-d H:i:s');
        }

        $query = '?' . http_build_query($params);
        $response = $this->makeRequest("/publishers/{$this->publisherId}/transactions", $query);

        $transCounter = 0;
        $transactions = [];
        $transactionsData = json_decode($response->getBody());

        if ($transactionsData != null) {
            foreach ($transactionsData as $transactionData) {
                foreach ($transactionData->parts as $transPart) {
                    $transaction = Transaction::createFromJson($transactionData, $transPart, $this->revShareEnabled);
                    $transactions[] = $transaction;
                }

                $transCounter++;
            }
        }

        // Check whether more iterations are needed:
        $totalItems = $response->getHeader('x-total-count');
        $currentPageTotal = $transCounter + $this->itemsPerPage * ($page - 1);

        // Retrieve more items when
        if ($totalItems > $currentPageTotal) {
            $transactions = array_merge($transactions, $this->getTransactions($startDate, $endDate, $page + 1));
        }

        return $transactions;
    }

    /**
     * @param        $resource
     * @param string $query
     * @return mixed
     * @throws DaisyconApiException
     */
    protected function makeRequest($resource, $query = ""): \GuzzleHttp\Psr7\Request
    {
        $uri = $this->endpoint . $resource;

        $request = $this->oAuthProvider->getAuthenticatedRequest(
            'GET',
            $uri . $query,
            $this->accessToken,
            ['headers' => ['Accept' => 'application/json']]
        );

        $response = $this->httpClient->send($request);

        if ($response->getStatusCode() != 200) {
            throw new DaisyconApiException($response->getBody()->getContents());
        }

        var_dump($response->getBody()->getContents());exit;

        return $response;
    }

    public function setTokens(string $accessToken)
    {
        $this->refreshToken();

        $this->accessToken = $accessToken;
    }
}
