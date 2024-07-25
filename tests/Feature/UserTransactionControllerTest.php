<?php

namespace Tests\Feature;

use App\Utils\DataProviderUtil;
use App\Utils\HttpStatusCodeUtil;
use App\Utils\UserTransactionStatusUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_index_endpoint()
    {
        $response = $this->get('/api/users/transactions');
        $response->assertStatus(HttpStatusCodeUtil::OK);
    }

    public function test_index_endpoint_2()
    {
        $filterCombinations = [
            [
                'provider' => DataProviderUtil::PROVIDER_X,
                'statusCode' => UserTransactionStatusUtil::AUTHORISED,
                'balanceMin' => 1000,
                'balanceMax' => 3000,
                'currency' => 'USD',
            ],
            [
                'provider' => DataProviderUtil::PROVIDER_X,
                'statusCode' => UserTransactionStatusUtil::DECLINE,
                'balanceMin' => 1000,
                'balanceMax' => 3000,
                'currency' => 'USD',
            ],
            [
                'provider' => DataProviderUtil::PROVIDER_X,
                'statusCode' => UserTransactionStatusUtil::REFUNDED,
                'balanceMin' => 1000,
                'balanceMax' => 3000,
                'currency' => 'USD',
            ],
            [
                'provider' => DataProviderUtil::PROVIDER_Y,
                'statusCode' => UserTransactionStatusUtil::AUTHORISED,
                'balanceMin' => 1000,
                'balanceMax' => 3000,
                'currency' => 'USD',
            ],
            [
                'provider' => DataProviderUtil::PROVIDER_Y,
                'statusCode' => UserTransactionStatusUtil::DECLINE,
                'balanceMin' => 1000,
                'balanceMax' => 3000,
                'currency' => 'USD',
            ],
            [
                'provider' => DataProviderUtil::PROVIDER_Y,
                'statusCode' => UserTransactionStatusUtil::REFUNDED,
                'balanceMin' => 1000,
                'balanceMax' => 3000,
                'currency' => 'USD',
            ]
        ];
        foreach ($filterCombinations as $filters) {
            $filtersArr = [];
            foreach ($filters as $key => $value)
                $filtersArr[] = "$key=$value";

            $response = $this->get('/api/users/transactions?' . implode('&', $filtersArr));
            $response->assertStatus(HttpStatusCodeUtil::OK);
        }
    }

    public function test_index_endpoint_3()
    {
        $filterCombinations = [
            [
                'provider' => 'ABC'
            ],
            [
                'statusCode' => 'processing'
            ],
            [
                'balanceMin' => 'x',
            ],
            [
                'balanceMax' => 'y',
            ],
            [
                'provider' => 'ABC',
                'statusCode' => 'processing',
                'balanceMin' => 'x',
                'balanceMax' => 'y'
            ]
        ];

        foreach ($filterCombinations as $filters) {
            $response = null;
            $filtersArr = [];
            foreach ($filters as $key => $value)
                $filtersArr[] = "$key=$value";

            $response = $this->get('/api/users/transactions?' . implode('&', $filtersArr));
            $response->assertStatus(HttpStatusCodeUtil::BAD_REQUEST);
        }
    }
}
