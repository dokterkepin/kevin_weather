<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Services\UserService;

class ControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp():void {
        parent::setUp();

    }

    public function testRegisterSuccessfully(){

        $response = $this->get("/register");
        $response = $this->post("/register", [
            "user_id" => "dokterkepin",
            "user_name" => "Kevin",
            "user_password" => "rahasiakita",
            "user_password_confirmation" => "rahasiakita",
            "user_email" => "btw.sneakythief33@gmail.com",

        ]);

        $response->assertRedirect("/login");
        $response = $this->followingRedirects()->get("/login");
        $response->assertSee("User Login");
    }

    public function testRegisterValidationFailed(){
        $response = $this->get("/register");
        $response = $this->post("/register", [
            "user_id" => "",
            "user_name" => "Kevin",
            "user_password" => "rahasiakitabersama",
            "user_password_confirmation" => "rahasiakitabersama",
            "user_email" => "anakganteng373@gmail.com",
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect("/register");
        $response = $this->followingRedirects()->get("/register");
        $response->assertSee("Welcome Home");

    }

    public function testRegisterDuplicate(){
        $this->post("/register", [
            "user_id" => "dokterkepin",
            "user_name" => "Kevin",
            "user_password" => "rahasiakitabersama",
            "user_password_confirmation" => "rahasiakitabersama",
            "user_email" => "anakganteng373@gmail.com",
        ]);

        $response = $this->get("/register");
        $response = $this->post("/register", [
            "user_id" => "dokterkepin",
            "user_name" => "Kevin",
            "user_password" => "rahasiakitabersama",
            "user_password_confirmation" => "rahasiakitabersama",
            "user_email" => "anakganteng373@gmail.com",
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect("/register");
        $response = $this->followingRedirects()->get("/register");
        $response->assertSee("Welcome Home");

    }

    public function testLoginSuccessfully(){
        $response = $this->get("/register");
        $response = $this->post("/register", [
            "user_id" => "dokterkepin",
            "user_name" => "Kevin",
            "user_password" => "rahasiakitabersama",
            "user_password_confirmation" => "rahasiakitabersama",
            "user_email" => "anakganteng373@gmail.com",
        ]);

        $response = $this->get("/login");
        $response = $this->post("/login", [
            "user_id" => "dokterkepin",
            "user_password" => "rahasiakitabersama",
        ]);
        $response->assertRedirect("/");
        $response = $this->followingRedirects()->get("/");
        $response->assertSee("Welcome Home Kevin");
    }

    public function testLoginValidationFailed(){
        $response = $this->get("/login");
        $response = $this->post("/login", [
            "user_id" => "",
            "user_password" => "",
        ]);
        $response->assertSessionHasErrors();
        $response->assertRedirect("/login");
        $response = $this->followingRedirects()->get("/login");
        $response->assertSee("User Login");
    }
    public function testLoginNotFound(){
        $response = $this->get("/login");
        $response = $this->post("/login", [
            "user_id" => "dokterkepin",
            "user_password" => "rahasiakitabersama",

        ]);
        $response->assertSessionHasErrors();
        $response->assertRedirect("/login");
        $response = $this->followingRedirects()->get("/login");
        $response->assertSee("User Login");
    }

    public function testUpdateSuccessfully(){
        $this->post("/register", [
            "user_id" => "dokterkepin",
            "user_name" => "Kevin",
            "user_password" => "rahasiakita",
            "user_password_confirmation" => "rahasiakita",
            "user_email" => "anakganteng373@gmail.com",
        ]);

        $this->post("/login", [
            "user_id" => "dokterkepin",
            "user_password" => "rahasiakita",
        ]);

        $response = $this->post("/update", [
            "user_name" => "Daniel",
            "user_password" => "rahasiasaja",
            "user_password_confirmation" => "rahasiasaja",
        ]);

        $response->assertRedirect("/");
        $response = $this->followingRedirects()->get("/");
        $response->assertSee("Welcome Home Daniel");
    }

    public function testUpdateValidationFailed(){
        $this->post("/register", [
            "user_id" => "dokterkepin",
            "user_name" => "Kevin",
            "user_password" => "rahasiakita",
            "user_password_confirmation" => "rahasiakita",
            "user_email" => "anakganteng373@gmail.com",
        ]);

        $this->post("/login", [
            "user_id" => "dokterkepin",
            "user_password" => "rahasiakita",
        ]);

        $response = $this->get("/update");
        $response = $this->post("/update", [
            "user_name" => "",
            "user_password" => "",
            "user_password_confirmation" => "",
        ]);

        $response->assertRedirect("/update");
        $response->assertSessionHasErrors();
        $response = $this->followingRedirects()->get("/update");
        $response->assertSee("Update User");
        $response->assertSee("The user name field is required.");
        $response->assertSee("The user password field is required.");

    }

    public function testUpdateTheSame(){
        $this->post("/register", [
            "user_id" => "dokterkepin",
            "user_name" => "Kevin",
            "user_password" => "rahasiakita",
            "user_password_confirmation" => "rahasiakita",
            "user_email" => "anakganteng373@gmail.com",
        ]);

        $this->post("/login", [
            "user_id" => "dokterkepin",
            "user_password" => "rahasiakita",
        ]);

        $response = $this->post("/update", [
            "user_name" => "Kevin",
            "user_password" => "rahasiakita",
            "user_password_confirmation" => "rahasiakita",
        ]);

        $response->assertRedirect("/update");
        $response->assertSessionHasErrors("update_error");
        $response = $this->followingRedirects()->get("/update");
        $response->assertSee("Update User");
    }

    public function testLogOutSuccessfully(){
        $this->post("/register", [
            "user_id" => "dokterkepin",
            "user_name" => "Kevin",
            "user_password" => "rahasiakita",
            "user_password_confirmation" => "rahasiakita",
            "user_email" => "anakganteng373@gmail.com",
        ]);

        $this->post("/login", [
            "user_id" => "dokterkepin",
            "user_password" => "rahasiakita",
        ]);
        $this->get("/");
        $response = $this->post("/logout");
        $response->assertRedirect("/login");
        $response->assertSessionHas("logoutSuccess");
        $this->assertGuest();

    }

    public function testDeleteSuccessfully(){
        $this->post("/register", [
            "user_id" => "dokterkepin",
            "user_name" => "Kevin",
            "user_password" => "rahasiakita",
            "user_password_confirmation" => "rahasiakita",
            "user_email" => "anakganteng373@gmail.com",
        ]);

        $this->post("/login", [
            "user_id" => "dokterkepin",
            "user_password" => "rahasiakita",
        ]);

        $this->get("/");
        $response = $this->post("/delete");
        $response->assertRedirect("/register");
        $response->assertSessionHas("deleteSuccess");
        $this->assertGuest();
        $this->assertDatabaseMissing("user_account", ["user_id" => "dokterkepin"]);

    }


}
