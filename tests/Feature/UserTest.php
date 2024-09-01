<?php

namespace Tests\Feature;

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

}
