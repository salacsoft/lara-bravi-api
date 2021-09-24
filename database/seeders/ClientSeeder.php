<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $gender = $faker->randomElement(['male', 'female']);

    	foreach (range(1,200) as $index) {
            DB::table('clients')->insert([
                'uuid' => Str::random(30),
                'account_manager_uuid' => Str::random(30),
                'company_uuid' => Str::random(30),
                'client_code' => Str::random(10),
                'client_name' => $faker->name($gender),
                'client_address' => $faker->address,
                'is_active' => 1,
                'created_at' => $faker->date($format = 'Y-m-d h:i:s', $max = 'now')
            ]);
        }
    }
}
