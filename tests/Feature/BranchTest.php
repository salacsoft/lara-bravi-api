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

		public function testGetBranchListingWithPagination(){
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

		public function testUpdateBranch(){
			// create a fake branch
			$branch = Branch::factory()->create();

			$user = User::factory()->create();
			$this->actingAs($user);


			// dummy data to update
			$payload = array (
				'id' => $branch->id,
				'uuid' => $branch->uuid,
				'client_uuid' => $branch->client_uuid,
				'branch_code' => $branch->branch_code,
				'branch_name' => $branch->branch_name,
				'branch_address' => $branch->branch_address,
			);

			// action 1 verify branch if existing on database before proceed in 

			$response = $this->withHeaders([
				'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
				'Accept' => 'application/json',
			])
			->get(route("branch.get", ["id" => $branch->id]))
			->assertStatus(200)
			->assertJson(fn (AssertableJson $json) => 
				$json->where("id", $branch->id)
							->where("uuid", $branch->uuid)
							->where("client_uuid", $branch->client_uuid)
							->has("id")
							->etc()
			);

			// test scenario for error finding branch using id
			$response = $this->withHeaders([
				'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
				'Accept' => 'application/json',
			])
			->get(route("branch.get", ["id" => 6]))
			->assertStatus(404);

			// action2
			$response = $this->withHeaders([
				'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
				'Accept' => 'application/json',
			])
			// localhost:8000/api/v1/branches/{id}/data
			->patch(route('branch.update',['id' => $payload['id']]), $payload)
			->assertStatus(200)
			->assertJson(fn (AssertableJson $json) => 
				$json->has("data")
							->where("success",true)
							->etc()
							->has("data", fn(AssertableJson $json2) => 
							$json2->where("id", $payload["id"])
										->where("uuid", $payload["uuid"])
										->has("id")
										->etc()
							)
			);
		}
}
