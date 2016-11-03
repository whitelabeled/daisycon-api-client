<?php
namespace whitelabeled\DaisyconApi;

use DateTime;
use Httpful\Request;

class DaisyconClient {
    private $username;
    private $password;

    protected $endpoint = 'https://services.daisycon.com';

    private $token;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;

        $this->generateToken();
    }

    protected function generateToken() {
        $request = Request::post($this->endpoint . '/authenticate')
            ->sendsJson()
            ->body(['username' => $this->username, 'password' => $this->password]);

        $response = $request->send();

        if ($response->hasErrors()) {
            throw new \Exception('Auth failure');
        }

        $this->token = $response->body;
    }
}