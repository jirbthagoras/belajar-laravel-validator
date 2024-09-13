<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class ValidatorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testValidator(): void
    {
        $data = [
            "username" => "admin",
            "password" => "12345"
        ];

        $rules = [
            "username" => "required",
            "password" => "required"
        ];

        $validator = Validator::make($data, $rules);

        assertNotNull($validator);
    }
}
