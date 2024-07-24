<?php

namespace Database\Factories;

use App\Utils\DataProviderUtil;
use App\Utils\UserTransactionStatusUtil;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'source' => DataProviderUtil::getAllProviders()[rand(0,1)],
            'email' => $this->faker->email(),
            'status' => UserTransactionStatusUtil::getAllStatuses()[rand(0, 2)],
            'balance' => $this->faker->randomNumber(4),
            'currency' => $this->faker->currencyCode(),
            'transaction_id' => $this->faker->uuid(),
            'transaction_date' => $this->faker->date()
        ];
    }
}