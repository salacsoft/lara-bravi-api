<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AccountManager;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountManagerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        Parent::setUp();
        $this->setupFaker();
        $this->user = User::factory()->create();
    }

    public function testToGetListOfAccountManager()
    {
        $am = AccountManager::factory()->create();
        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->get(route("account-manager.list",["search" => $am->first_name]))
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->has("data")
                ->has("links")
                ->has("meta")
                ->etc()
        );
    }


    public function testToExportAccountManagers()
    {
        $am = AccountManager::factory()->count(100)->create();
        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->post(route("account-manager.export"))
        ->assertStatus(200)
        ->assertDownload();
    }

}
