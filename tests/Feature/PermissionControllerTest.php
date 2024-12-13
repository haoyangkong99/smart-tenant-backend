<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class PermissionControllerTest extends TestCase
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
        $response = $this->getJson('/api/auth/permissions');
        echo $response->getContent();
        $response->assertStatus(200);
    }
}