<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CquoinController;

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
});



