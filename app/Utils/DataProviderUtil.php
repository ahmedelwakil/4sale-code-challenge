<?php

namespace App\Utils;

use App\DataProviders\DataProviderX;
use App\DataProviders\DataProviderY;

class DataProviderUtil
{
    const PROVIDER_X = 'provider_x';
    const PROVIDER_Y = 'provider_y';

    /**
     * @return string[]
     */
    public static function getAllProviders()
    {
        return [
            self::PROVIDER_X,
            self::PROVIDER_Y,
        ];
    }

    /**
     * @return string[]
     */
    public static function getProvidersMapping()
    {
        return [
            self::PROVIDER_X => DataProviderX::class,
            self::PROVIDER_Y => DataProviderY::class,
        ];
    }

    /**
     * @param string $provider
     * @return string|null
     */
    public static function getProviderClass(string $provider)
    {
        return self::getProvidersMapping()[$provider] ?? null;
    }
}