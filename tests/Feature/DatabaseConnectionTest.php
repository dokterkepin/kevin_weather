<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseConnectionTest extends TestCase
{
    use RefreshDatabase;
    public function testConnection(){
        $response = DB::connection()->getPdo();
        $this->assertNotNull($response);
    }

    public function testPerform(){
        DB::table("user_account")->insert([
           "user_id" => "halo678",
            "user_name" => "Nathan",
            "user_password" => bcrypt("sixfordie"),
            "user_email" => "nathanieltjang@gmail.com"
        ]);

        $user = DB::table("user_account")->where("user_name", "Nathan")->first();
        $this->assertNotNull($user);
    }

    public function testConnectionSingleton(){
        $response1 = DB::connection()->getPdo();
        $response2 = DB::connection()->getPdo();

        $this->assertSame($response1, $response2);
    }
}
