<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        Parent::setUp();
        $this->setupFaker();
        $this->user = User::factory()->create();
    }

    public function testCreateGroup()
    {

        $this->actingAs($this->user);

        $payload = ["group_name" => $this->faker->lastName . " Group"];
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
        ])
        ->post(route('group.store'), $payload)
        ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                    $json->has('data')
                        ->has("success")
                        ->etc()
            );
    }


    public function testUpdateGroup()
    {
        $this->actingAs($this->user);
        $group = Group::factory()->create();
        $payload = ["group_name" => $this->faker->lastName . " Group"];
        $response = $this->withHeaders([
                'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
            ])
            ->patch(route('group.update', ['id' => $group["id"]]), $payload)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has("data",fn (AssertableJSON $json2) =>
                        $json2->where("id", $group["id"])
                        ->where("uuid", $group["uuid"])
                        ->has("id")
                        ->etc()
                    )
                    ->has("success")
                    ->etc()
                );
    }


    public function testFindGroup()
    {
        $this->actingAs($this->user);
        $group = Group::factory()->create();
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
        ])
        ->get(route('group.get', ['id' => $group['id']]))
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) =>
            $json->where("id", $group["id"])
            ->where("uuid", $group["uuid"])
            ->etc()
        );
    }


    public function testRemoveGroup()
    {
        $this->actingAs($this->user);
        $group = Group::factory()->create();

        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
        ])
        ->delete(route('group.destroy', ["id" => $group->id]))
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->has("message")
                ->where("success", true)
                ->etc()
        );

        //CHECK IF THE GROUP WONT EXISTS ON FIND
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
        ])
        ->get(route('group.get', ['id' => $group->id]))
        ->assertStatus(404);

        //CHECK IF THE GROUP IS SOFT DELETED
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
        ])
        ->get(route("group.find.soft-delete", ["id" => $group->id]))
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->has("deleted_at")
                ->where("uuid", $group->uuid)
                ->etc()
        );
    }


    function testMissingPayload()
    {
        $this->actingAs($this->user);

        $payload = ["group_name" => null];

        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])
        ->post(route('group.store'), $payload)
        ->assertStatus(422);
    }


    function testDuplicateGroup()
    {
        $this->actingAs($this->user);
        $group = Group::factory()->create();
        $payload = ["group_name" => $group->group_name];

        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])
        ->post(route("group.store"), $payload)
        ->assertStatus(422);
    }


    function testToFindNotExistingGroup()
    {
        $this->actingAs($this->user);
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])
        ->get(route("group.get",["id" => 0]))
        ->assertStatus(404);
    }


    function testToDeleteNotExistingGroup()
    {
        $this->actingAs($this->user);
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->delete(route("group.destroy",["id" => 0]))
        ->assertStatus(404);
    }


    function testFindSoftDeleteGroup()
    {
        $group = Group::factory()->create();
        $this->actingAs($this->user);
        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->delete(route('group.destroy', ["id" => $group->id]))
        ->assertStatus(200);

        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->get(route("group.get", ["id" => $group->id]))
        ->assertStatus(404);

        $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->get(route("group.find.soft-delete", ["id" => $group->id]))
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->has("deleted_at")
                ->where("uuid", $group->uuid)
                ->etc()
        );
    }


    function testSearchGroup()
    {
        $group = Group::factory()->create();
        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->get(route("group.list",["search" => $group->group_name]))
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
                ->has("links")
                ->has("meta")
                ->etc()
        );
    }


    function testExportGroup()
    {
        $group = Group::factory()->count(100)->create();
        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->get(route("group.export"))
        ->assertDownload()
        ->assertStatus(200);
    }


}
