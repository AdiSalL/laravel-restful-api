<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::post("/users", [UserController::class, "register"]);
Route::post("/users/login", [UserController::class, "login"]);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get("/users/current", [UserController::class, "get"]);
});