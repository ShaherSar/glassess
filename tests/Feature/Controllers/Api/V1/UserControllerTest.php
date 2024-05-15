<?php

namespace Tests\Feature\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            
        ]);
    }

    public function test_admin_can_create_a_lens_returns_success()
    {
        $response = $this->getJson('/api/v1/', [
            'Authorization' => 'Bearer ' . $adminToken,
        ]);
    }

    public function test_user_can_create_a_lens_returns_error()
    {

    }
}
