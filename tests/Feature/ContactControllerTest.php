<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Factories\ContactFactory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tests\Feature\ControllerTest;
class ContactControllerTest extends TestCase
{
    use RefreshDatabase;
    private $token = '';
    public function setUp(): void
    {
        parent::setUp();

        $user = User::all()->first();
        $token = Auth::guard('api')->login($user);
        $this->token = 'Bearer ' . $token;
    }
    public function testIndex()
    {
        // Create sample contacts using factory

        Contact::factory()->count(3)->create();
        // ['Authorization'=>$this->token]
        $response = $this->getJson('/api/auth/contacts',);

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'email',
                    // ... other fields
                ]
            ]);

        // Test when no contacts exist
        Contact::truncate();
        $response = $this->getJson('/api/auth/contacts');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone'=>'01412345678',
            'address'=>'Tun Razak Exchange'

        ];

        $response = $this->postJson('/api/auth/contacts', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'contact' => [
                    'id',
                    'name',
                    'email',
                    // ... other fields
                ]
            ]);
    }

    public function testShow()
    {
        // Create a contact using factory
        $contact = Contact::factory()->create();

        $response = $this->getJson('/api/auth/contacts/' . $contact->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                // ... other fields
            ]);

        // Test when contact not found
        $response = $this->getJson('/api/auth/contacts/'.$contact->id);
        $response->assertStatus(200);
    }

    public function testUpdate()
{
    $contact = Contact::factory()->create();

    $data = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'phone' => '01234567890',
        'address' => 'New Address',
    ];

    $response = $this->putJson('/api/auth/contacts/' . $contact->id, $data);

    $response->assertStatus(200)
        ->assertJsonFragment($data);
}
public function testDelete()
{
    $contact = Contact::factory()->create();

    $response = $this->deleteJson('/api/auth/contacts/' . $contact->id);

    $response->assertStatus(200); // No Content expected for successful deletion
}
}