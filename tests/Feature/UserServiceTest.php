<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\UserModel;
use App\Services\UserService;
use App\Exceptions\ValidationException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;



class UserServiceTest extends TestCase
{

    use RefreshDatabase;
    protected UserService $userService;
    protected UserModel $userModel;

    protected function setUp(): void {
        parent::setUp();
        $this->userService = new UserService();
    }
    Public function testLogicRegisterSuccessfully(){

        $data = [
            "user_id" => "dokterkepin33",
            "user_name" => "Kevin",
            "user_password" => "rahasiakita",
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

    public function testLogicLoginSuccessfully()
    {
        $data = [
            "user_id" => "dokterkepin33",
            "user_name" => "Kevin",
            "user_password" => "rahasiakitabersama",
            "user_email" => "btw.sneakythief33@gmail.com"
        ];

        $user = $this->userService->register($data);


        $data2 = [
            'user_id' => 'dokterkepin33',
            'user_password' => 'rahasiakitabersama',
        ];

        $response = $this->userService->login($data2);
        $this->assertEquals($data['user_id'], $response->user_id);
    }

    public function testLogicUpdateSuccessfully(){
        $dataRegister = [
            "user_id" => "dokterkepin33",
            "user_name" => "Kevin",
            "user_password" => "rahasiakitabersama",
            "user_email" => "btw.sneakythief33@gmail.com"
        ];

        $user = $this->userService->register($dataRegister);

        $dataLogin = [
            'user_id' => 'dokterkepin33',
            'user_password' => 'rahasiakitabersama',
        ];

        $this->userService->login($dataLogin);

        $dataUpdate = [
            "user_name" => "Daniel",
            "user_password" => "rahasiasaja"
        ];

        $response = $this->userService->update($dataUpdate);
        $this->assertDatabaseHas("user_account", [
            "user_name" => "Daniel"
        ]);
        $this->assertNotEquals($dataRegister["user_id"], $response->user_name);
    }

    public function testLogicLogoutSuccessfully(){
        $dataRegister = [
            "user_id" => "dokterkepin33",
            "user_name" => "Kevin",
            "user_password" => "rahasiakitabersama",
            "user_email" => "btw.sneakythief33@gmail.com"
        ];
        $user = $this->userService->register($dataRegister);

        $dataLogin = [
            'user_id' => 'dokterkepin33',
            'user_password' => 'rahasiakitabersama',
        ];
        $this->userService->login($dataLogin);

        $this->userService->logout();
        $this->assertFalse(Auth::check());
    }

    public function testLogicDeleteSuccessfully(){
        $dataRegister = [
            "user_id" => "dokterkepin33",
            "user_name" => "Kevin",
            "user_password" => "rahasiakitabersama",
            "user_email" => "btw.sneakythief33@gmail.com"
        ];
        $user = $this->userService->register($dataRegister);

        $dataLogin = [
            'user_id' => 'dokterkepin33',
            'user_password' => 'rahasiakitabersama',
        ];
        $this->userService->login($dataLogin);

        $this->userService->delete();
        $this->assertFalse(Auth::check());
        $this->assertDatabaseMissing("user_account", ["user_id" => "dokterkepin33"]);
    }



}
