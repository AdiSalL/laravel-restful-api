<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
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
            "Authorization" => " "
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

    public function testGetSuccess() {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        
        $this->get("/api/contacts/". $contact->id, [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "first_name" => "test",
                "last_name" => "test",
                "email" => "adiefsal@gmail.com",

            ]
        ]);
    }

    public function testNotFound() {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        
        $this->get("/api/contacts/". $contact->id + 1, [
            "Authorization" => "test"
        ])->assertStatus(404);
    }

    public function testGetOtherContact(){ 
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        
        $this->get("/api/contacts/". $contact->id, [
            "Authorization" => "test2"
        ])->assertStatus(404);
    }

    public function testUpdateSuccess() {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        
        $this->put("/api/contacts/". $contact->id, 
        [
            "first_name" => "test2",
            "last_name" => "test2",
            "email" => "adiefsal2@gmail.com",
        ],
        [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "first_name" => "test2",
                "last_name" => "test2",
                "email" => "adiefsal2@gmail.com",

            ]
        ]);
        
    }

    public function testUpdateFailed() {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        
        $this->put("/api/contacts/". $contact->id, 
        [
            "first_name" => "",
            "last_name" => "test2",
            "email" => "adiefsal2@gmail.com",
        ],
        [
            "Authorization" => "test"
        ])->assertStatus(400);
    }
}
