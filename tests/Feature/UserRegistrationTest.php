<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        Parent::setUp();
        $this->setupFaker();
    }


    /**
     * @test
     */
    public function RegisterUser()
    {
        $payload = array(
            "first_name"    => $this->faker->firstNameMale,
            "last_name"     => $this->faker->lastName,
            "email"         => $this->faker->safeEmail,
            "password"      => "Password",
            "confirm_password" => "Password",
            '_token' => csrf_token()
        );

        // Actions
        $response = $this->post(route('cquion.users.store'), $payload);

        // Assertions
        $response->assertStatus(201)
                ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', "New user successfuly registered")
                    ->has("data")
                );
    }


    /**
     * @test
     */
    public function testPasswordMismatch()
    {
        $payload = array(
            "first_name"    => $this->faker->firstNameMale,
            "last_name"     => $this->faker->lastName,
            "email"         => $this->faker->safeEmail,
            "password"      => "Password",
            "confirm_password" => "Password12",
            '_token' => csrf_token()
        );

        // Actions
        $this->withHeaders([
                'Accept' => 'application/json',
                'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
                ])
            ->post(route('cquion.users.store'), $payload)
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                    $json->has('message')
                         ->has("errors")
            );
    }

    /**
     * @test
     */
    public function testMissingFirstname()
    {
        $payload = array(
            "last_name"     => $this->faker->lastName,
            "email"         => $this->faker->safeEmail,
            "password"      => "Password",
            "confirm_password" => "Password",
            '_token' => csrf_token()
        );

        // Actions
        $this->withHeaders([
                'Accept' => 'application/json',
                'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
                ])
            ->post(route('cquion.users.store'), $payload)
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                    $json->has('message')
                         ->has("errors")
            );
    }
}
