<?php

namespace App\DataProviders;

use App\Utils\UserTransactionStatusUtil;

abstract class BaseDataProvider
{
    protected $source;
    
    protected $balanceKey;
    protected $currencyKey;
    protected $emailKey;
    protected $statusKey;
    protected $createdAtKey;
    protected $idKey;

    protected $authorisedStatusCode;
    protected $declineStatusCode;
    protected $refundedStatusCode;

    private $statusMapping;

    abstract protected function getSource();
    abstract protected function getIdKey();
    abstract protected function getBalanceKey();
    abstract protected function getCurrencyKey();
    abstract protected function getEmailKey();
    abstract protected function getStatusKey();
    abstract protected function getCreatedAtKey();
    abstract protected function getAuthorisedStatusCode();
    abstract protected function getDeclineStatusCode();
    abstract protected function getRefundedStatusCode();

    public function __construct()
    {
        $this->source = $this->getSource();

        $this->idKey = $this->getIdKey();
        $this->balanceKey = $this->getBalanceKey();
        $this->currencyKey = $this->getCurrencyKey();
        $this->emailKey = $this->getEmailKey();
        $this->statusKey = $this->getStatusKey();
        $this->createdAtKey = $this->getCreatedAtKey();

        $this->authorisedStatusCode = $this->getAuthorisedStatusCode();
        $this->declineStatusCode = $this->getDeclineStatusCode();
        $this->refundedStatusCode = $this->getRefundedStatusCode();

        $this->statusMapping = $this->createStatusMapping();
    }

    /**
     * @param $record
     * @return array
     */
    public function parse($record)
    {
        return [
            'source' => $this->source,
            'transaction_id' => $record->{$this->idKey},
            'balance' => $record->{$this->balanceKey},
            'currency' => $record->{$this->currencyKey},
            'email' => $record->{$this->emailKey},
            'status' => $this->statusMapping[$record->{$this->statusKey}] ?? null,
            'transaction_date' => $record->{$this->createdAtKey},
        ];
    }

    /**
     * @return array
     */
    private function createStatusMapping()
    {
        return [
            $this->authorisedStatusCode => UserTransactionStatusUtil::AUTHORISED,
            $this->declineStatusCode => UserTransactionStatusUtil::DECLINE,
            $this->refundedStatusCode => UserTransactionStatusUtil::REFUNDED,
        ];
    }

    /**
     * @return array
     */
    public function getDataSchema()
    {
        return [
            'source' => $this->source,
            'id_key' => $this->idKey,
            'balance_key' => $this->balanceKey,
            'currency_key' => $this->currencyKey,
            'email_key' => $this->emailKey,
            'status_key' => $this->statusKey,
            'createdAt_key' => $this->createdAtKey,
            'authorised_status_code' => $this->authorisedStatusCode,
            'decline_status_code' => $this->declineStatusCode,
            'refunded_status_code' => $this->refundedStatusCode,
        ];
    }
}