<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\AccountManager;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountManagerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountManager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "uuid"  => Str::random(20),
            "account_pin"  => Str::random(8),
            "account_code" => Str::random(10),
            "first_name" => $this->faker->firstName,
            "last_name" => $this->faker->lastName,
            "mobile_no" => $this->faker->phoneNumber,
            "photo" => $this->faker->imageUrl($width = 640, $height = 480)
        ];
    }
}
