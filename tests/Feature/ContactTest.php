<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function testCreateSuccess(){ 
        $this->seed([UserSeeder::class]);
        $this->post("/api/contacts", [
            "first_name" => "Adi",
            "last_name" => "Salafudin",
            "email" => "adi@gmail.com",
            "phone" => "08123456789"
        ], [
            "Authorization" => "test"
        ])
        ->assertStatus(201)
        ->assertJson([
            "data" => [
                "first_name" => "Adi",
                "last_name" => "Salafudin",
                "email" => "adi@gmail.com",
                "phone" => "08123456789"
            ]
        ]
        ); 
    }

    public function testCreateFailed(){ 
        $this->seed([UserSeeder::class]);
        $this->post("/api/contacts", [
            "first_name" => "",
            "last_name" => "",
            "email" => "mail",
            "phone" => "08123456789"
        ], [
            "Authorization" => "test"
        ])
        ->assertStatus(400); 
    }

    public function testCreateUnauthorized(){ 
        $this->seed([UserSeeder::class]);
        $this->post("/api/contacts", [
            "first_name" => "Adi",
            "last_name" => "Salafudin",
            "email" => "adi@gmail.com",
            "phone" => "08123456789"
        ], [
            "Authorization" => ""
        ])
        ->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => [
                    "unauthorized"
                ]
            ]
        ]
        ); 
    }
}
