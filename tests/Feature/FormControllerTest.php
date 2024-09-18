<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FormControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testSuccess(): void
    {
        $response = $this->post('/login', [
            'username' => '',
            'password' => '',
        ]);

        $response->assertStatus(400);
    }
}
