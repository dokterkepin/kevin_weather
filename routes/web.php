<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use inertia\inertia;


Route::get("/register", [UserController::class, "register"])->name("register");
Route::post("/register", [UserController::class, "postRegister"])->name("postRegister");

Route::middleware("guest")->group(function(){
    Route::get("/login", [UserController::class, "login"])->name("login");
    Route::post("/login", [UserController::class, "postLogin"])->name("postLogin");
});

Route::middleware("auth")->group(function(){
    Route::get("/update", [UserController::class, "update"])->name("update");
    Route::post("/update", [UserController::class, "postUpdate"])->name("postUpdate");
    Route::get("/", [UserController::class, "index"])->name("index");
    Route::post("/logout", [UserController::class, "postLogout"])->name("postLogout");
    Route::post("/delete", [UserController::class, "postDelete"])->name("postDelete");
});

Route::get("/weather", function(){
    return  inertia::render("Weather");
});

