<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function testCreateSuccess() {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post("/api/contacts/" . $contact->id . "/addresses", 
        [
            "street" => "test",
            "city" => "test",
            "province" => "test",
            "country" => "test",
            "postal_code" => "1401",
        ],
        [
            "Authorization" => "test"
        ],
    )->assertStatus(201)
    ->assertJson([
        "data" => [
            "street" => "test",
            "city" => "test",
            "province" => "test",
            "country" => "test",
            "postal_code" => "1401",
        ]
    ]);
    }

    public function testCreateFailed() {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post("/api/contacts/" . $contact->id . "/addresses", 
        [
            "street" => "test",
            "city" => "test",
            "province" => "test",
            "country" => "",
            "postal_code" => "1401",
        ],
        [
            "Authorization" => "test"
        ],
    )->assertStatus(400);
    }

    public function testCreateContactNotFound() {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post("/api/contacts/" . $contact->id + 1 . "/addresses", 
        [
            "street" => "test",
            "city" => "test",
            "province" => "test",
            "country" => "",
            "postal_code" => "1401",
        ],
        [
            "Authorization" => "test"
        ],
    )->assertStatus(400);
    }

    public function testGetSuccess() {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $this->get("/api/contacts/" . $address->contact_id . "/addresses/" . $address->id, 
        [
            "Authorization" => "test"
        ],)
        ->assertStatus(200)
        ->assertJson([
            "data" => [
                "street" => "Jl. Test",
                "city" => "Test",
                "province" => "Test",
                "country" => "Test",
                "postal_code" => "1401",
            ]
        ]);
    }

    
    public function testGetNotFound() {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $this->get("/api/contacts/" . $address->contact_id . "/addresses/" . ($address->id + 1), 
        [
            "Authorization" => "test"
        ],)
        ->assertStatus(404)
        ->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ],
            ]
        ]);
    }
    
    public function testUpdateSuccess() {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put("/api/contacts/" . $address->contact_id . "/addresses/" . $address->id, 
        [
            "street" => "Jl. Test Baru",
            "city" => "Test Baru",
            "province" => "Test Baru",
            "country" => "Test Baru",
            "postal_code" => "1402",

        ],
        [
            "Authorization" => "test"
        ],)
        ->assertStatus(200)
        ->assertJson([
            "data" => [
                "street" => "Jl. Test Baru",
                "city" => "Test Baru",
                "province" => "Test Baru",
                "country" => "Test Baru",
                "postal_code" => "1402",
            
            ]
   ]);
    }

     
    public function testUpdateFailed() {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put("/api/contacts/" . $address->contact_id . "/addresses/" . $address->id, 
        [
            "street" => "Test Baru",
            "city" => "",
            "province" => "Test Baru",
            "country" => "",
            "postal_code" => "1402",

        ],
        [
            "Authorization" => "test"
        ],)
        ->assertStatus(400);
    }   

    public function testUpdateNotFound() {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $this->put("/api/contacts/" . $address->contact_id . "/addresses/" . ($address->id + 1), 
        [
            "street" => "Test Baru",
            "city" => "",
            "province" => "Test Baru",
            "country" => "update",
            "postal_code" => "1402",

        ],
        [
            "Authorization" => "test"
        ],)
        ->assertStatus(404)        
        ->assertJson([
            "errors" => [
                "message" => ["not found"]
            ]
   ]);;
    }  

    public function testDeleteSuccess() {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $this->delete("/api/contacts/" . $address->contact_id . "/addresses/" . $address->id,
        [], 
        [
            "Authorization" => "test"
        ],)
        ->assertStatus(200)        
        ->assertJson([
            
                "message" => ["Deleted Successfully"]
            
   ]);;
    }

    // public function testDeleteNotFound() {
    //     $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    //     $address = Address::query()->limit(1)->first();
    //     $this->delete("/api/contacts/" . $address->contact_id . "/addresses/" . ($address->id + 1),
    //     [], 
    //     [
    //         "Authorization" => "test"
    //     ],)
    //     ->assertStatus(401);
    // }

    public function testListSuccess() {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->get("/api/contacts/" . $contact->id . "/addresses" ,
        [
            "Authorization" => "test"
        ],)
        ->assertStatus(200);
    }

    public function testListNotFound() {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->get("/api/contacts/" . ($contact->id + 1) . "/addresses" ,
        [
            "Authorization" => "test"
        ],)
        ->assertStatus(404);
    }
}
