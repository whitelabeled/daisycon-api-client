<?php

namespace whitelabeled\DaisyconApi;

use DateTime;

class Transaction
{
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
     * @var string
     */
    public $refererUrl;

    /**
     * @var double Effective commission for this sale
     */
    public $commissionAmount;

    /**
     * @var double Total commission for this sale
     */
    public $totalCommissionAmount;

    /**
     * @var boolean Whether the commission for this sale is shared with a service provider
     */
    public $sharedCommission;

    /**
     * @var double Percentage of the total sale commission
     */
    public $commissionPercentage;

    /**
     * @var integer Revenue share partner ID
     */
    public $revenueSharePartner;

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
     * @var string
     */
    public $publisherDescription;

    /**
     * @var int
     */
    public $mediaId;

    /**
     * @var string
     */
    public $mediaName;


    /**
     * Create a Transaction object from two JSON objects
     * @param \stdClass $transData Transaction data
     * @param \stdClass $part Part data
     * @param boolean $revShareEnabled Revenue share enabled
     * @return Transaction
     */
    public static function createFromJson($transData, $part, $revShareEnabled)
    {
        $transaction = new Transaction();

        $transaction->id = $transData->affiliatemarketing_id;
        $transaction->partId = $part->id;
        $transaction->program = $transData->program_name;
        $transaction->programId = $transData->program_id;
        $transaction->ipAddress = $transData->anonymous_ip;

        $transaction->mediaId = $part->media_id;
        $transaction->mediaName = $part->media_name;

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
        $transaction->publisherDescription = $part->publisher_description;
        $transaction->extra1 = $part->extra_1;
        $transaction->extra2 = $part->extra_2;
        $transaction->extra3 = $part->extra_3;
        $transaction->extra4 = $part->extra_4;
        $transaction->extra5 = $part->extra_5;
        $transaction->refererUrl = $part->referer_click;

        $transaction->commissionAmount = $part->commission;
        $transaction->revenue = $part->revenue;

        $revShareMatch = preg_match('/^([0-9]+)\|([,\.0-9]+)$/', $transaction->subId2, $revShareMatches);

        if ($revShareEnabled && $revShareMatch == 1) {
            $transaction->revenueSharePartner = $revShareMatches[1];
            $partnerCommission = $revShareMatches[2];
            $transaction->sharedCommission = true;
            $transaction->totalCommissionAmount = $partnerCommission + $transaction->commissionAmount;
            $transaction->commissionPercentage = $transaction->commissionAmount / $transaction->totalCommissionAmount * 100;
        } else {
            $transaction->sharedCommission = false;
            $transaction->totalCommissionAmount = $transaction->commissionAmount;
            $transaction->sharedCommission = 0;
            $transaction->commissionPercentage = 100;
        }

        // Optional data:
        if (isset($transData->device_type)) {
            $transaction->deviceType = $transData->device_type;
        }

        return $transaction;
    }

    /**
     * Parse a date
     * @param $date string Date/time string
     * @return DateTime|null
     */
    private static function parseDate($date)
    {
        if ($date == null) {
            return null;
        } else {
            return new \DateTime($date);
        }
    }
}