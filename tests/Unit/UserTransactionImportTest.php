<?php

namespace Tests\Unit;

use App\Services\UserTransactionService;
use App\Utils\DataProviderUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTransactionImportTest extends TestCase
{
    use RefreshDatabase;

    /** @var UserTransactionService */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(UserTransactionService::class);
    }

    public function test_provider_x_import_1()
    {
        $importData = [
            [
                "parentAmount" => 200,
                "Currency" => "USD",
                "parentEmail" => "parent1@parent.eu",
                "statusCode" => 1,
                "registerationDate" => "2018-11-30",
                "parentIdentification" => "d3d29d70-1d25-11e3-8591-034165a3a613"
            ],
            [
                "parentAmount" => 300,
                "Currency" => "USD",
                "parentEmail" => "parent2@parent.eu",
                "statusCode" => 2,
                "registerationDate" => "2018-11-30",
                "parentIdentification" => "d3d29d70-1d25-11e3-8591-034165a3a614"
            ],
            [
                "parentAmount" => 400,
                "Currency" => "USD",
                "parentEmail" => "parent3@parent.eu",
                "statusCode" => 3,
                "registerationDate" => "2018-11-30",
                "parentIdentification" => "d3d29d70-1d25-11e3-8591-034165a3a615"
            ]
        ];

        $this->service->importFromJsonString(json_encode($importData), DataProviderUtil::PROVIDER_X);
        $this->assertDatabaseCount('user_transactions', 3);
    }

    public function test_provider_x_import_2()
    {
        $importData = [
            [
                "parentAmount" => 200,
                "Currency" => "USD",
                "parentEmail" => "parent1@parent.eu",
                "statusCode" => 1,
                "registerationDate" => "2018-11-30",
                "parentIdentification" => "d3d29d70-1d25-11e3-8591-034165a3a613"
            ],
            [
                "parentAmount" => 300,
                "Currency" => "USD",
                "parentEmail" => "parent2@parent.eu",
                "statusCode" => 2,
                "registerationDate" => "2018-11-30",
                "parentIdentification" => "d3d29d70-1d25-11e3-8591-034165a3a614"
            ],
            [
                "parentAmount" => 400,
                "Currency" => "USD",
                "parentEmail" => "parent3@parent.eu",
                "statusCode" => 3,
                "registerationDate" => "2018-11-30",
                "parentIdentification" => "d3d29d70-1d25-11e3-8591-034165a3a615"
            ]
        ];

        $this->expectExceptionMessage('Please check that the correct data provider is specified.');
        $this->service->importFromJsonString(json_encode($importData), DataProviderUtil::PROVIDER_Y);
        $this->assertDatabaseCount('user_transactions', 0);
    }

    public function test_provider_y_import_1()
    {
        $importData = [
            [
                "balance" => 200,
                "currency" => "USD",
                "email" => "parent1@parent.eu",
                "status" => 100,
                "created_at" => "22/12/2018",
                "id" => "4fc2-a8d1"
            ],
            [
                "balance" => 300,
                "currency" => "USD",
                "email" => "parent2@parent.eu",
                "status" => 200,
                "created_at" => "22/12/2018",
                "id" => "4fc2-a8d2"
            ],
            [
                "balance" => 400,
                "currency" => "USD",
                "email" => "parent3@parent.eu",
                "status" => 300,
                "created_at" => "22/12/2018",
                "id" => "4fc2-a8d3"
            ]
        ];

        $this->service->importFromJsonString(json_encode($importData), DataProviderUtil::PROVIDER_Y);
        $this->assertDatabaseCount('user_transactions', 3);
    }

    public function test_provider_y_import_2()
    {
        $importData = [
            [
                "balance" => 200,
                "currency" => "USD",
                "email" => "parent1@parent.eu",
                "status" => 100,
                "created_at" => "22/12/2018",
                "id" => "4fc2-a8d1"
            ],
            [
                "balance" => 300,
                "currency" => "USD",
                "email" => "parent2@parent.eu",
                "status" => 200,
                "created_at" => "22/12/2018",
                "id" => "4fc2-a8d2"
            ],
            [
                "balance" => 400,
                "currency" => "USD",
                "email" => "parent3@parent.eu",
                "status" => 300,
                "created_at" => "22/12/2018",
                "id" => "4fc2-a8d3"
            ]
        ];

        $this->expectExceptionMessage('Please check that the correct data provider is specified.');
        $this->service->importFromJsonString(json_encode($importData), DataProviderUtil::PROVIDER_X);
        $this->assertDatabaseCount('user_transactions', 0);
    }

    public function test_mix_import_1()
    {
        $importData = [
            [
                "parentAmount" => 200,
                "Currency" => "USD",
                "parentEmail" => "parent1@parent.eu",
                "statusCode" => 1,
                "registerationDate" => "2018-11-30",
                "parentIdentification" => "d3d29d70-1d25-11e3-8591-034165a3a613"
            ],
            [
                "balance" => 200,
                "currency" => "USD",
                "email" => "parent1@parent.eu",
                "status" => 100,
                "created_at" => "22/12/2018",
                "id" => "4fc2-a8d1"
            ]
        ];

        $this->expectExceptionMessage('Please check that the correct data provider is specified.');
        $this->service->importFromJsonString(json_encode($importData), DataProviderUtil::PROVIDER_X);
        $this->assertDatabaseCount('user_transactions', 0);
    }

    public function test_mix_import_2()
    {
        $importData = [
            [
                "parentAmount" => 200,
                "Currency" => "USD",
                "parentEmail" => "parent1@parent.eu",
                "statusCode" => 1,
                "registerationDate" => "2018-11-30",
                "parentIdentification" => "d3d29d70-1d25-11e3-8591-034165a3a613"
            ],
            [
                "balance" => 200,
                "currency" => "USD",
                "email" => "parent1@parent.eu",
                "status" => 100,
                "created_at" => "22/12/2018",
                "id" => "4fc2-a8d1"
            ]
        ];

        $this->expectExceptionMessage('Please check that the correct data provider is specified.');
        $this->service->importFromJsonString(json_encode($importData), DataProviderUtil::PROVIDER_Y);
        $this->assertDatabaseCount('user_transactions', 0);
    }
}
