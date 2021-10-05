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
							->has('message')
							->etc()
			);

    }
}
