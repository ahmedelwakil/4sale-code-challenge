<?php

namespace Tests\Unit;

use App\Models\UserTransaction;
use App\Services\UserTransactionService;
use App\Utils\DataProviderUtil;
use App\Utils\UserTransactionStatusUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTransactionListTest extends TestCase
{
    use RefreshDatabase;

    /** @var UserTransactionService */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(UserTransactionService::class);
        $this->artisan('db:seed');
    }

    public function test_index()
    {
        $count = UserTransaction::query()->count();
        $list = $this->service->list();

        self::assertCount($count, $list);
    }

    public function test_index_provider_filter()
    {
        $countProviderX = UserTransaction::query()->where('source', '=', DataProviderUtil::PROVIDER_X)->count();
        $countProviderY = UserTransaction::query()->where('source', '=', DataProviderUtil::PROVIDER_Y)->count();
        $listProviderX = $this->service->list(['provider' => DataProviderUtil::PROVIDER_X]);
        $listProviderY = $this->service->list(['provider' => DataProviderUtil::PROVIDER_Y]);

        self::assertCount($countProviderX, $listProviderX);
        self::assertCount($countProviderY, $listProviderY);
    }

    public function test_index_status_filter()
    {
        $countStatusAuthorised = UserTransaction::query()->where('status', '=', UserTransactionStatusUtil::AUTHORISED)->count();
        $countStatusDecline = UserTransaction::query()->where('status', '=', UserTransactionStatusUtil::DECLINE)->count();
        $countStatusRefunded = UserTransaction::query()->where('status', '=', UserTransactionStatusUtil::REFUNDED)->count();
        $listStatusAuthorised = $this->service->list(['statusCode' => UserTransactionStatusUtil::AUTHORISED]);
        $listStatusDecline = $this->service->list(['statusCode' => UserTransactionStatusUtil::DECLINE]);
        $listStatusRefunded = $this->service->list(['statusCode' => UserTransactionStatusUtil::REFUNDED]);

        self::assertCount($countStatusAuthorised, $listStatusAuthorised);
        self::assertCount($countStatusDecline, $listStatusDecline);
        self::assertCount($countStatusRefunded, $listStatusRefunded);
    }

    public function test_index_min_balance_filter()
    {
        $averageBalance = round(UserTransaction::query()->avg('balance'));
        $countRecordsWithMinBalance = UserTransaction::query()->where('balance', '>=', $averageBalance)->count();
        $listRecordsWithMinBalance = $this->service->list(['balanceMin' => $averageBalance]);

        self::assertCount($countRecordsWithMinBalance, $listRecordsWithMinBalance);
    }

    public function test_index_max_balance_filter()
    {
        $averageBalance = round(UserTransaction::query()->avg('balance'));
        $countRecordsWithMaxBalance = UserTransaction::query()->where('balance', '<=', $averageBalance)->count();
        $listRecordsWithMaxBalance = $this->service->list(['balanceMax' => $averageBalance]);

        self::assertCount($countRecordsWithMaxBalance, $listRecordsWithMaxBalance);
    }

    public function test_index_currency_filter()
    {
        $topCurrencies = UserTransaction::query()
            ->select(['currency', DB::raw('COUNT(*) as count')])
            ->groupBy('currency')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->keyBy('currency')
            ->toArray();

        foreach ($topCurrencies as $currency => $data) {
            $listCurrency = $this->service->list(['currency' => $currency]);
            self::assertCount($data['count'], $listCurrency);
        }
    }

    public function test_index_combined_filters()
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
            $count = UserTransaction::query()
                ->where('source', '=', $filters['provider'])
                ->where('status', '=', $filters['statusCode'])
                ->where('balance', '>=', $filters['balanceMin'])
                ->where('balance', '<=', $filters['balanceMax'])
                ->where('currency', '=', $filters['currency'])
                ->count();
            $list = $this->service->list($filters);
            self::assertCount($count, $list);
        }
    }
}
