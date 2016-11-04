# Daisycon API client

[![Latest Stable Version](https://img.shields.io/packagist/v/whitelabeled/daisycon-api-client.svg)](https://packagist.org/packages/whitelabeled/daisycon-api-client)
[![Total Downloads](https://img.shields.io/packagist/dt/whitelabeled/daisycon-api-client.svg)](https://packagist.org/packages/whitelabeled/daisycon-api-client)
[![License](https://img.shields.io/packagist/l/whitelabeled/daisycon-api-client.svg)](https://packagist.org/packages/whitelabeled/daisycon-api-client)

Library to retrieve sales from the Daisycon publisher API.

Usage:

```php
<?php
require 'vendor/autoload.php';

$client = new \whitelabeled\DaisyconApi\DaisyconClient('username', 'password', '123456');
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
            [commission] => 25
            [revenue] => 0
            [extra1] => 
            [extra2] => 
            [extra3] => 
            [extra4] => 
            [extra5] => 
        )

)
*/
```

## License

© Whitelabeled BV

MIT license, see [LICENSE.txt](LICENSE.txt) for details.