<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
use Illuminate\Support\Facades\Log;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::factory()->count(100)->create();
    }
}
