<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Facades\App\Services\UserService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAuthenticationTest extends TestCase
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
    public function testLogin()
    {
        $payload = array(
            "first_name"    => $this->faker->firstNameMale,
            "last_name"     => $this->faker->lastName,
            "email"         => $this->faker->safeEmail,
            "password"      => "Password",
            "confirm_password" => "Password",
            '_token' => csrf_token()
        );

        // regsiter user
        $this->withHeaders([
                'Accept' => 'application/json',
                'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
                ])
            ->post(route('cquion.users.store'), $payload)
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                    $json->where('success', true)
                         ->has("message")
                         ->has("data")

            );

        // attempt to login
        $this->withHeaders([
            'Accept' => 'application/json',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
            ])
        ->post(route('user.login'), $payload)
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
                    $json->where('success', true)
                    ->has("message")
                    ->has("data", fn (AssertableJson $json) =>
                        $json->where("email", $payload["email"])
                            ->etc()
                    )
        );
    }

    /**
     * @test
     */
    public function testInvalidCredentials()
    {
        $payload = array(
            "email"         => $this->faker->safeEmail,
            "password"      => "Password",
            '_token' => csrf_token()
        );

        // attempt to login
        $this->withHeaders([
            'Accept' => 'application/json',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
            ])
        ->post(route('user.login'), $payload)
        ->assertStatus(401)
        ->assertJson(fn (AssertableJson $json) =>
                    $json->where("success", false)
                        ->has("message")
                        ->etc()
        );
    }



    /**
     * @test
     */
    public function testMissingPassword()
    {
        $payload = array(
            "email"         => $this->faker->safeEmail,
            '_token' => csrf_token()
        );

        // attempt to login
        $this->withHeaders([
            'Accept' => 'application/json',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
            ])
        ->post(route('user.login'), $payload)
        ->assertStatus(422)
        ->assertJson(fn (AssertableJson $json) =>
                    $json->has("message")
                        ->has("errors")
                        ->etc()
        );
    }

    /**
     * @test
     */
    public function testUserbanned()
    {
        $payload = array(
            "first_name"    => $this->faker->firstNameMale,
            "last_name"     => $this->faker->lastName,
            "email"         => $this->faker->safeEmail,
            "password"      => "Password",
            "confirm_password" => "Password",
            '_token' => csrf_token()
        );

        // regsiter user
        $this->withHeaders([
                'Accept' => 'application/json',
                'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
                ])
            ->post(route('cquion.users.store'), $payload)
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                    $json->where('success', true)
                         ->has("message")
                         ->has("data")

            );

        $credential = array(
            "email"         => $payload["email"],
            "password"      => Str::random(10)
        );

        // attempt to login
        $this->withHeaders([
            'Accept' => 'application/json',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
            ])
        ->post(route('user.login'), $credential)
        ->assertStatus(401);


        // attempt to login
        $this->withHeaders([
            'Accept' => 'application/json',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
            ])
        ->post(route('user.login'), $credential)
        ->assertStatus(401);

        // banned user
        $this->withHeaders([
            'Accept' => 'application/json',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
            ])
        ->post(route('user.login'), $credential)
        ->assertStatus(409);

        $user = UserService::findByEmail($credential["email"]);
        $this->assertNotNull($user["banned_until"]);
    }



}
