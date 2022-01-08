<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\AccountManager;
use Illuminate\Http\UploadedFile;
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


    public function testToCreateAccountManager()
    {
        $payload = [
            "account_code"  => "0001",
            "account_pin"   => "12344",
            "first_name"    => "Joseph",
            "last_name"     => "Salac",
            "mobile_no"     => "09165107852",
            "photo"         => UploadedFile::fake()->image('avatar.jpg')
        ];

        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'multipart/form-data',
            'Content-Type' => 'multipart/form-data'
        ])
        ->post(route("account-manager.list"), $payload)
        ->assertStatus(201)
        ->assertJson(fn (AssertableJson $json) =>
            $json->where("account_code", $payload["account_code"])
                ->where("account_pin", $payload["account_pin"])
                ->where("mobile_no", $payload["mobile_no"])
                ->etc()
        );
    }


    public function testToReadAccountManager()
    {
        $am = AccountManager::factory()->create();
        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])
        ->get(route("account-manager.get", ["id" => $am->id]))
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->where("account_code", $am->account_code)
                ->where("account_pin", $am->account_pin)
                ->where("mobile_no", $am->mobile_no)
                ->etc()
        );
    }


    public function testToUpdateAccountManager()
    {
        $am = AccountManager::factory()->create();
        $payload = [
            "_method"       => "patch",
            "account_code"  => "0001",
            "account_pin"   => "12344",
            "first_name"    => "Joseph",
            "last_name"     => "Salac",
            "mobile_no"     => "09165107852",
            "photo"         => UploadedFile::fake()->image('avatar.jpg')
        ];

        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'multipart/form-data',
            'Content-Type' => 'multipart/form-data'
        ])
        ->post(route("account-manager.update", ["id" => $am->id]), $payload)
        ->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->where("account_code", $payload["account_code"])
                ->where("account_pin", $payload["account_pin"])
                ->where("mobile_no", $payload["mobile_no"])
                ->etc()
        );
    }


    public function testToCheckDuplicateAccountManager()
    {
        $am = AccountManager::factory()->create();
        $payload = json_decode($am, TRUE);
        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->post(route("account-manager.store"), $payload)
        ->assertStatus(422);
    }


    public function testToValidateMissingPayload()
    {
        $payload = [
            "account_pin"   => "12344",
            "first_name"    => "Joseph",
            "last_name"     => "Salac",
            "mobile_no"     => "09165107852",
            "photo"         => UploadedFile::fake()->image('avatar.jpg')
        ];

        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])
        ->post(route("account-manager.store"), $payload)
        ->assertStatus(422);
    }


    public function testToRemoveAccountManager()
    {
        $am = AccountManager::factory()->create();

        $this->actingAs($this->user);
        $response = $this->withHeaders([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ])
        ->delete(route("account-manager.destroy", ["id" => $am->id]))
        ->assertStatus(200);
    }


}
