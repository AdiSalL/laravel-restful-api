<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess() {
        $this->post("/api/users", [
            "username" => "salafudin",
            "password" => "rahasia",
            "name" =>  "Adi Salafudin"
        ])->assertStatus(201)
        ->assertJson([
            "data" => [
                "username" => "salafudin",
                "name" =>  "Adi Salafudin"
            ]
        ]);
    }

    public function testRegisterFailed() {
        $this->post("/api/users", [
            "username" => "",
            "password" => "",
            "name" =>  ""
        ])->assertStatus(400);
    }

    public function testRegisterUsernameAlreadyExists() {
        $this->testRegisterSuccess();
        $this->post("/api/users", [
            "username" => "salafudin",
            "password" => "rahasia",
            "name" =>  "Adi Salafudin"
        ])->assertStatus(400)
        ->assertJson([
            "errors"=> [
                "username"=> [
                    "username is already registered",
                ],
            ]
        
        ]);
    }

    public function testLoginSuccess() {
        $this->seed([UserSeeder::class]);
        $this->post("/api/users/login", [
            "username" => "test",
            "password" => "test",
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "username" => "test",
                "name" => "test",
            ]
        ]);
        $user = User::where("username", "test")->first();
        self::assertNotNull($user->token);
        self::assertEquals($user->username, "test");
    }

    public function testLoginFailed() {
        $this->seed([UserSeeder::class]);
        $this->post("/api/users/login", [
            "username" => "",
            "password" => "",
        ])->assertStatus(400);
        
     
    }

}
