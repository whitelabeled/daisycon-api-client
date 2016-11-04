<?php
namespace whitelabeled\DaisyconApi;

use DateTime;

class Transaction {
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $partId;

    /**
     * @var DateTime
     */
    public $transactionDate;

    /**
     * @var DateTime
     */
    public $clickDate;

    /**
     * @var DateTime
     */
    public $approvalDate;

    /**
     * @var DateTime
     */
    public $lastModifiedDate;

    /**
     * @var string
     */
    public $programId;

    /**
     * @var string
     */
    public $countryId;

    /**
     * @var string
     */
    public $regionId;

    /**
     * @var string
     */
    public $gender;

    /**
     * @var string
     */
    public $age;

    /**
     * @var string
     */
    public $deviceType;

    /**
     * @var string
     */
    public $program;

    /**
     * @var string
     */
    public $ipAddress;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $disapprovedReason;

    /**
     * @var string
     */
    public $subId;

    /**
     * @var string
     */
    public $subId2;

    /**
     * @var string
     */
    public $subId3;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var double
     */
    public $commission;

    /**
     * @var double
     */
    public $revenue;

    /**
     * @var string
     */
    public $extra1;

    /**
     * @var string
     */
    public $extra2;

    /**
     * @var string
     */
    public $extra3;

    /**
     * @var string
     */
    public $extra4;

    /**
     * @var string
     */
    public $extra5;


    /**
     * Create a Transaction object from two JSON objects
     * @param $transData \stdClass Transaction data
     * @param $part      \stdClass Part data
     * @return Transaction
     */
    public static function createFromJson($transData, $part) {
        $transaction = new Transaction();

        $transaction->id = $transData->affiliatemarketing_id;
        $transaction->partId = $part->id;
        $transaction->program = $transData->program_name;
        $transaction->programId = $transData->program_id;
        $transaction->ipAddress = $transData->anonymous_ip;

        $transaction->transactionDate = self::parseDate($part->date);
        $transaction->clickDate = self::parseDate($part->date_click);
        $transaction->approvalDate = self::parseDate($part->approval_date);
        $transaction->lastModifiedDate = self::parseDate($part->last_modified);

        $transaction->status = $part->status;
        $transaction->disapprovedReason = $part->disapproved_reason;
        $transaction->subId = $part->subid;
        $transaction->subId2 = $part->subid_2;
        $transaction->subId3 = $part->subid_3;
        $transaction->reference = $part->referencenumber;
        $transaction->extra1 = $part->extra_1;
        $transaction->extra2 = $part->extra_2;
        $transaction->extra3 = $part->extra_3;
        $transaction->extra4 = $part->extra_4;
        $transaction->extra5 = $part->extra_5;

        $transaction->commission = $part->commission;
        $transaction->revenue = $part->revenue;

        $transaction->age = $transData->age;
        $transaction->gender = $transData->gender;
        $transaction->deviceType = $transData->device_type;
        $transaction->countryId = $transData->country_id;
        $transaction->regionId = $transData->region_id;

        return $transaction;
    }

    /**
     * Parse a date
     * @param $date string Date/time string
     * @return DateTime|null
     */
    private static function parseDate($date) {
        if ($date == null) {
            return null;
        } else {
            return new \DateTime($date);
        }
    }
}