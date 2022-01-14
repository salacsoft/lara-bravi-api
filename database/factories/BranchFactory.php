<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $client = Client::factory()->create();
        return [
            'uuid' => Str::random(20),
            'client_uuid' => $client->uuid,
            'branch_code' => Str::random(10),
            'branch_name' => $this->faker->streetName . " branch",
            'branch_address' => $this->faker->address,
        ];
    }
}
