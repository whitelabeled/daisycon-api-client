<?php
namespace whitelabeled\DaisyconApi;

class Transaction {
    public $id;
    public $partId;

    public $transactionDate;
    public $clickDate;
    public $approvalDate;
    public $lastModifiedDate;

    public $programId;
    public $countryId;
    public $regionId;
    public $gender;
    public $age;
    public $deviceType;
    public $program;
    public $ipAddress;
    public $status;
    public $disapprovedReason;

    public $subId;
    public $subId2;
    public $subId3;
    public $reference;

    public $commission;
    public $revenue;

    public $extra1;
    public $extra2;
    public $extra3;
    public $extra4;
    public $extra5;

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

    private static function parseDate($date) {
        if ($date == null) {
            return null;
        } else {
            return new \DateTime($date);
        }
    }
}