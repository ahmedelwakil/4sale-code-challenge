<?php

namespace App\DataProviders;

class DataProviderY extends BaseDataProvider
{
    protected function getSource()
    {
        return 'DataProviderY';
    }

    protected function getIdKey()
    {
        return 'id';
    }

    protected function getBalanceKey()
    {
        return 'balance';
    }

    protected function getCurrencyKey()
    {
        return 'currency';
    }

    protected function getEmailKey()
    {
        return 'email';
    }

    protected function getStatusKey()
    {
        return 'status';
    }

    protected function getCreatedAtKey()
    {
        return 'created_at';
    }

    protected function getAuthorisedStatusCode()
    {
        return 100;
    }

    protected function getDeclineStatusCode()
    {
        return 200;
    }

    protected function getRefundedStatusCode()
    {
        return 300;
    }
}