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

    public function testGetSuccess(){ 
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current", [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "username" => "test",
                "name" => "test"
            ]
        ]);
    }

    public function testGetUnauthorized(){ 
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current")->assertStatus(401);
    }

    public function testGetInvalidToken() {
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current", [
            "Authorization" => "salah"
        ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => [
                    "unauthorized"
                ]
            ]
        ]);
    }

    public function testUpdateNameSuccess() {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where("username", "test")->first();
        $this->patch('/api/users/current',
        [
            'name' => 'Adi'
        ],
        [
            'Authorization' => 'test'
        ]
    )->assertStatus(200)
        ->assertJson([
            'data' => [
                'username' => 'test',
                'name' => 'Adi'
            ]
        ]);
        $newUser = User::where("username", "test")->first();
        self::assertNotEquals($oldUser->name, $newUser->name);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current',
            [
                'password' => 'baru'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test'
                ]
            ]);

        $newUser = User::where('username', 'test')->first();
        self::assertNotEquals($oldUser->password, $newUser->password);
    }


    public function testUpdateFailed() {
        
    }
}
