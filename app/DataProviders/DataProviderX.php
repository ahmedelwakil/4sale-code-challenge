<?php

namespace App\DataProviders;

class DataProviderX extends BaseDataProvider
{
    protected function getSource()
    {
        return 'DataProviderX';
    }

    protected function getIdKey()
    {
        return 'parentIdentification';
    }

    protected function getBalanceKey()
    {
        return 'parentAmount';
    }

    protected function getCurrencyKey()
    {
        return 'Currency';
    }

    protected function getEmailKey()
    {
        return 'parentEmail';
    }

    protected function getStatusKey()
    {
        return 'statusCode';
    }

    protected function getCreatedAtKey()
    {
        return 'registerationDate';
    }

    protected function getAuthorisedStatusCode()
    {
        return 1;
    }

    protected function getDeclineStatusCode()
    {
        return 2;
    }

    protected function getRefundedStatusCode()
    {
        return 3;
    }
}