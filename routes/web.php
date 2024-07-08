<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", [UserController::class, "index"])->name("index");

Route::get("/register", [UserController::class, "create"])->name("register.create");
Route::post("/register", [UserController::class, "store"])->name("register.store");
