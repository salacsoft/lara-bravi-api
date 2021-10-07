<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BranchTest extends TestCase
{
		use WithFaker, RefreshDatabase;

		public function setUp(): void
		{
			Parent::setUp();
			$this->setupFaker();
		}
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateNewBranch()
    {
			// important!:  using a faker must see a valid formatter in faker github 

			// genarate a fake user to validate actions
			$user = User::factory()->create();
			$this->actingAs($user);

			// datas 
			$payload = [
				'client_uuid' => Str::random(20),
				'branch_code' => Str::random(10),
				'branch_name' =>  $this->faker->streetName,
				'branch_address' => $this->faker->address,
				'token' => csrf_token()
			];

			// actions
			$this->withHeaders([
				'HTTP_X_REQUEST_WITH' => 'XMLHttpRequest'
			])
			->post(route('branch.store'), $payload)
			->assertStatus(201)
			->assertJson(fn (AssertableJson $json) => 
				$json->has('data')
							->has('success')
							->etc()
			);

    }

		public function testGetBranchListingWithPagination()
		{
			Branch::factory()->count(3)->create();

			// generate fake user
			$user = User::factory()->create();
			$this->actingAs($user);

			// actions
			$response = $this->withHeaders([
				'HTTP_X_REQUEST_WITH' => 'XMLHttpRequest',
				'Accept' => 'application/json'
			])
			->get(route('branch.list'))
			->assertStatus(200)
			->assertJson( fn (AssertableJson $json) => 
				$json->has('data')
						->has('links')
						->etc()
			);
		}
}
