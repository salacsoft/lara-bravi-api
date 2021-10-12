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
                        ->has("success")
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
                $json->where("id", $client->id)
                    ->where("uuid", $client->uuid)
                    ->has("id")
                    ->etc()
            );
    }


    /**
     * get client list
     * @test
     */
    public function testUpdateClientRecord()
    {
        $client =  Client::factory()->create();
        $payload = array(
            "id" => $client->id,
            "uuid"   => "asdasd123123",
            "client_code" => $client->client_code,
            "client_name" => $client->client_name,
            "client_address" => $client->client_address,
        );
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
                $json->where("id", $client->id)
                    ->where("uuid", $client->uuid)
                    ->has("id")
                    ->etc()
            );

        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            ])
            ->patch(route('client.update', ["id" => $payload["id"]]), $payload)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has("data")
                    ->where("success",true)
                    ->etc()
                    ->has("data",fn (AssertableJSON $json2) =>
                        $json2->where("id", $payload["id"])
                        ->where("uuid", $payload["uuid"])
                        ->has("id")
                        ->etc()
                    )
            );
    }

    /**
     * get client list
     * @test
     */
    public function testUpdateClientRecordWithInvalidId()
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
                $json->where("id", $client->id)
                    ->where("uuid", $client->uuid)
                    ->has("id")
                    ->etc()
            );

        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            ])
            ->get(route('client.update', ["id" => 9156874]))
            ->assertStatus(404);
    }


    // /**
    //  * get client list
    //  * @test
    //  */
    public function testUpdateClientRecordUsedClientCode()
    {
        $client =  Client::factory()->create();
        $client2 =  Client::factory()->create();
        $payload = array(
            "id" => $client->id,
            "client_code" => $client2->client_code,
            "client_name" => $client->client_name,
            "client_address" => $client->client_address,
        );
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
            ])
            ->patch(route('client.update', ["id" => $payload["id"]]), $payload)
            ->assertStatus(422);
    }


}
