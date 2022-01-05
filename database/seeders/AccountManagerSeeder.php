<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountManager;

class AccountManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountManager::factory()->count(100)->create();
    }
}
