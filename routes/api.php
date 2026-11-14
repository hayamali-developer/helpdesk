<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;


Route::post("register",[AuthController::class,"register"])->name("register");
Route::post("login",[AuthController::class,"login"])->name("login");
Route::middleware("auth:sanctum")->group(function () {
Route::apiResource("tickets",TicketController::class)->only(["index","store","update"]);
Route::post("update/role",[AuthController::class,"updateRole"])->name("update-role");
});