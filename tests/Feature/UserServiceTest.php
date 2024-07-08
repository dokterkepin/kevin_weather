<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\UserModel;
use App\Services\UserService;
use App\Exceptions\ValidationException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;



class UserServiceTest extends TestCase
{

    use RefreshDatabase;
    protected UserService $userService;

    protected function setUp(): void {
        parent::setUp();
        $this->userService = new UserService();
    }
    Public function testRegister(){

        $data = [
            "user_id" => "dokterkepin33",
            "user_name" => "Kevin",
            "user_password" => "rahasiakita",
            "user_password_confirmation" => "rahasiakita",
            "user_email" => "btw.sneakythief33@gmail.com"
        ];

        $user = $this->userService->register($data);

        $this->assertDatabaseHas("user_account", [
            "user_id" => "dokterkepin33",
            "user_name" => "Kevin",
            "user_email" => "btw.sneakythief33@gmail.com"
        ]);

        $this->assertTrue(Hash::check("rahasiakita", $user->user_password));

    }
}
