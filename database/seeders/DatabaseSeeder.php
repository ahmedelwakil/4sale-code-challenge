<?php

namespace Database\Seeders;

use App\Models\UserTransaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        UserTransaction::factory()->count(100)->create();
    }
}
