# Daisycon API client

[![Latest Stable Version](https://img.shields.io/packagist/v/whitelabeled/daisycon-api-client.svg)](https://packagist.org/packages/whitelabeled/daisycon-api-client)
[![Total Downloads](https://img.shields.io/packagist/dt/whitelabeled/daisycon-api-client.svg)](https://packagist.org/packages/whitelabeled/daisycon-api-client)
[![License](https://img.shields.io/packagist/l/whitelabeled/daisycon-api-client.svg)](https://packagist.org/packages/whitelabeled/daisycon-api-client)

Library to retrieve sales from the Daisycon publisher API.

Usage:

### Login

```php
<?php
require 'vendor/autoload.php';

session_start();

$client = new \whitelabeled\DaisyconApi\DaisyconClient(
    '123456',
    '848840-9900301-99494595-3994984',
    'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    'http://localhost/verify.php'
);
$login = $client->login();

$_SESSION['state'] = $login->state;
$_SESSION['pkce'] = $login->pkceCode;

echo 'Login URL: ' . $login->loginUrl;
```

### Verify

```php
<?php
require 'vendor/autoload.php';

session_start();

$client = new \whitelabeled\DaisyconApi\DaisyconClient(
    '123456',
    '848840-9900301-99494595-3994984',
    'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    'http://localhost/verify.php'
);

$refreshToken = $client->verifyAuthCode($_SESSION['state'], $_SESSION['pkce'], $_GET['state'], $_GET['code']);

// Store refreshtoken in database or persistent storage
```

### Get transactions


```php
<?php
require 'vendor/autoload.php';

$client = new \whitelabeled\DaisyconApi\DaisyconClient(
    '123456',
    '848840-9900301-99494595-3994984',
    'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    'http://localhost/verify.php'
);

// Refresh token, store new token in DB:
$refreshToken = $client->refreshAccessToken($refreshToken);

// Optional:
//$client->mediaIds = ['666666', '777777'];

$transactions = $client->getTransactions(new DateTime('2016-10-30 00:00:00'));

/*
 * Returns:
Array
(
    [0] => whitelabeled\DaisyconApi\Transaction Object
        (
            [id] => 1KMDIMF49503095MFJULCM
            [partId] => F7I6
            [transactionDate] => DateTime Object
                (
                    [date] => 2016-10-30 22:07:22.000000
                    [timezone_type] => 3
                    [timezone] => Europe/Berlin
                )

            [clickDate] => DateTime Object
                (
                    [date] => 2016-10-30 21:54:09.000000
                    [timezone_type] => 3
                    [timezone] => Europe/Berlin
                )

            [approvalDate] => 
            [lastModifiedDate] => DateTime Object
                (
                    [date] => 2016-10-30 22:07:22.000000
                    [timezone_type] => 3
                    [timezone] => Europe/Berlin
                )

            [programId] => 9999
            [countryId] => 222
            [regionId] => 0
            [gender] => 
            [age] => 0
            [deviceType] => pc
            [program] => Advertisements Inc.
            [ipAddress] => ?.22.33.44
            [status] => open
            [disapprovedReason] => 
            [subId] => 222
            [subId2] => 958503
            [subId3] => 
            [reference] => 
            [commissionAmount] => 6
            [totalCommissionAmount] => 6
            [sharedCommission] => 0
            [commissionPercentage] => 100
            [revenueSharePartner] => 
            [revenue] => 0
            [extra1] => 
            [extra2] => 
            [extra3] => 
            [extra4] => 
            [extra5] => 
            [publisherDescription] => Exciting product
            [mediaId] => 123456
            [mediaName] => Super interesting website
        )

)
*/
```

## License

© Keuze.nl BV

MIT license, see [LICENSE.txt](LICENSE.txt) for details.
