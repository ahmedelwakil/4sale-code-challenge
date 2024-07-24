<?php

namespace App\Utils;

class UserTransactionStatusUtil
{
    const AUTHORISED = 'authorised';
    const DECLINE = 'decline';
    const REFUNDED = 'refunded';

    /**
     * @return string[]
     */
    public static function getAllStatuses()
    {
        return [
            self::AUTHORISED,
            self::DECLINE,
            self::REFUNDED,
        ];
    }
}