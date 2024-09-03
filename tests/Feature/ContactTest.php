<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\SearchSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Container\Attributes\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log as FacadesLog;
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

    public function testDeleteSuccess() {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->delete("/api/contacts/". $contact->id,[],
        [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => true
        ]);

    }

    public function testDeleteNotFound() {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->delete("/api/contacts/". $contact->id + 1,[],
        [
            "Authorization" => "test"
        ])->assertStatus(401)
        // ->assertJson([
        //     "errors" => [
        //         "message" => [
        //             "not found"
        //         ]
        //     ]
        // ]);
        ;
    }

    public function testSearchByFirstName() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get("/api/contacts?name=first", [
            "Authorization" => "test"
        ])
        ->assertStatus(200)
        ->json();

        FacadesLog::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response["data"]));
        self::assertEquals(20, $response["meta"]["total"]);
        
    }

    
    public function testSearchByLastName() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get("/api/contacts?name=first", [
            "Authorization" => "test"
        ])
        ->assertStatus(200)
        ->json();

        FacadesLog::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response["data"]));
        self::assertEquals(20, $response["meta"]["total"]);
        
    }

        
    public function testSearchByEmail() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get("/api/contacts?email=test", [
            "Authorization" => "test"
        ])
        ->assertStatus(200)
        ->json();

        FacadesLog::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response["data"]));
        self::assertEquals(20, $response["meta"]["total"]);
        
    }

    public function testSearchByPhone() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get("/api/contacts?phone=8", [
            "Authorization" => "test"
        ])
        ->assertStatus(200)
        ->json();

        FacadesLog::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response["data"]));
        self::assertEquals(20, $response["meta"]["total"]);
        
    }

    
    public function testSearchNotFound() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get("/api/contacts?name=tidakada", [
            "Authorization" => "test"
        ])
        ->assertStatus(200)
        ->json();

        FacadesLog::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(0, count($response["data"]));
        self::assertEquals(0, $response["meta"]["total"]);
        
    }

    public function testSearchWithPage() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $response = $this->get("/api/contacts?size=5&page=2", [
            "Authorization" => "test"
        ])
        ->assertStatus(200)
        ->json();

        FacadesLog::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(5, count($response["data"]));
        self::assertEquals(20, $response["meta"]["total"]);
        
    }
}
