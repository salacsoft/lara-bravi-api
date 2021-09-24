<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        Parent::setUp();
        $this->setupFaker();
    }

    /**
     * Test client registration
     * @test
     */
    public function testClientRegistration()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // Arrangements
        $payload = [
            'client_code' =>   Str::random(20),
            'client_name' =>  $this->faker->firstName,
            'client_address'  => $this->faker->address,
            '_token' => csrf_token()
        ];

        //actions
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
            ])
            ->post(route('client.store'), $payload)
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                    $json->has('data')
                        ->has("message")
                        ->etc()
            );
    }


    /**
     * Test client registration with incomplete data
     * @test
     */
    public function testClientRegistrationWithIncompleteData()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // Arrangements
        $payload = [
            'client_name' =>  $this->faker->firstName,
            'client_address'  => $this->faker->address,
            '_token' => csrf_token()
        ];

        //actions
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            ])
            ->post(route('client.store'), $payload)
            ->assertStatus(422);
    }

    /**
     * Test client registration with duplicate client code
     * @test
     */
    public function testClientRegistrationWithDuplicateClientCode()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // Arrangements
        $payload = [
            'client_code' => Str::random(20),
            'client_name' =>  $this->faker->firstName,
            'client_address'  => $this->faker->address,
            '_token' => csrf_token()
        ];

        //actions
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            ])
            ->post(route('client.store'), $payload)
            ->assertStatus(201);

        //actions
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            ])
            ->post(route('client.store'), $payload)
            ->assertStatus(422);
    }



    /**
     * get client list
     * @test
     */
    public function testClientListingWithPagination()
    {
        Client::factory()->count(5)->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        //actions
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            ])
            ->get(route('client.list'))
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data')
                    ->has("meta")
                    ->has("links")
                    ->etc()
            );
    }


    /**
     * get client list
     * @test
     */
    public function testClientGetOneRecord()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        //actions
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            ])
            ->get(route('client.get', ["id" => $client->id]))
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->etc()
                    ->has("data", fn ($json) =>
                        $json->where("id", $client->id)
                            ->where("uuid", $client->uuid)
                            ->has("id")
                            ->etc()
                )
            );
    }


}
