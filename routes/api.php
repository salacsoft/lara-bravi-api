<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CquoinController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\GroupController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post("login", [AuthController::class ,"login"])->name("user.login");
Route::post("cquoin-users", [CquoinController::class, "store"])->name("cquion.users.store");
Route::post("forgot-password", [AuthController::class, "forgotPassword"])->name("auth.forgot-password");
Route::post("reset-password", [AuthController::class, "resetPassword"])->name("auth.reset-password");


Route::prefix('v1')->middleware('auth:sanctum')->group(function(){
    Route::get("cquoin-users/{id}", [CquoinController::class, "getUser"]);
    Route::post("logout", [AuthController::class, "logOut"])->name("auth.logout");

    // clients
    Route::get("clients", [ClientController::class, "all"])->name("client.list");
    Route::get("clients/{id}", [ClientController::class, "get"])->name("client.get");
    Route::post("clients", [ClientController::class, "store"])->name("client.store");
    Route::patch("clients/{id}", [ClientController::class, "update"])->name("client.update");
    Route::delete("clients/{id}", [ClientController::class, "destroy"])->name("client.delete");
    Route::post("clients/file/export", [ClientController::class, "exportList"])->name("client.export");

    // branches
    Route::post("branches", [BranchController::class, "store"])->name("branch.store");
    Route::get("branches", [BranchController::class, "all"])->name("branch.list");
    Route::get("branches/file/export", [BranchController::class, "exportBranches"])->name("branch.export");
    Route::get("branches/{id}", [BranchController::class, "get"])->name("branch.get");
    Route::patch("branches/{id}", [BranchController::class, "update"])->name("branch.update");
    Route::delete("branches/{id}", [BranchController::class, "destroy"])->name("branch.destroy");


    //GROUPS
    Route::get("groups", [GroupController::class, "all"])->name("group.list");
    Route::post("groups", [GroupController::class, "store"])->name("group.store");
    Route::get("groups/{id}", [GroupController::class, "get"])->name("group.get");
    Route::patch("groups/{id}", [GroupController::class, "update"])->name("group.update");
    Route::delete("groups/{id}", [GroupController::class, "destroy"])->name("group.destroy");
    Route::post("groups/file/export", [GroupController::class, "export"])->name("group.export");
    Route::get("groups/soft-deleted/all", [GroupController::class, "allSoftDeleted"])->name("group.soft-deleted");
    Route::get("groups/soft-deleted/{id}", [GroupController::class, "findSoftDeleted"])->name("group.find.soft-delete");


});





