# Changelog

## v3.0.7

* Fix: bug in date parsing in `getTransactions` method

## v3.0.6

* Enh: improve date parsing in `getTransactions` method

## v3.0.5

* Enh: update league/oauth2-client to stable version v2.7.0

## v3.0.4

* Enh: remove nategood/httpful as required package

## v3.0.3

* Fix: more pagination bugs

## v3.0.2

* Fix: pagination bugs

## v3.0.1

* Fix: bug in `getTransactions` method

## v3.0.0

* Change authentication to OAuth

## v2.1.4

* Change authentication to OAuth Basic authentication

## v2.1.3

* Return referer URL in transactions

## v2.1.2

* Do not process `age`, `gender`, `deviceType`, `country_id` anymore (not present in Daisycon API anymore)

## v2.1.1

* Do not process `region_id` anymore

## v2.1.0

* Made `publisherId` in DaisyconClient protected instead of private

## v2.0.1

* Fixed a bug in revenue sharing

## v2.0.0

* Exceptions refactored to DaisyconApiException
* Commission renamed to commissionAmount
* Calculate total commission and revenue share percentage

## v1.1.1

* `mediaId` and `mediaName` added to Transaction objects

## v1.1.0

* Process `publisher_description` field

## v1.0.1

* Documentation improved

## v1.0.0

* First release
