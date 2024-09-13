<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

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

        self::assertTrue($validator->passes());
        self::assertFalse($validator->fails());
    }

    public function testMessageBag()
    {

        $data = [
            "username" => "",
            "password" => ""
        ];

        $rules = [
            "username" => "required",
            "password" => "required"
        ];

        $validator = Validator::make($data, $rules);

        self::assertTrue($validator->fails());

        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));

    }

    public function testValidationException()
    {

        $data = [
            "username" => "",
            "password" => "ese",
            "admin" => true,
        ];

        $rules = [
            "username" => "required",
            "password" => "required"
        ];

        $validator = Validator::make($data, $rules);

        try{

            $validator->validate();
            self::fail("Validation exception not thrown");

        } catch(ValidationException $exception) {

            self::assertNotNull($exception->validator);

            $message = $validator->getMessageBag();

            Log::error($message->toJson(JSON_PRETTY_PRINT));

        }

    }

    public function testMultipleValidationRules()
    {

        $data = [
            "username" => "admin",
            "password" => "admin"
        ];

        $rules = [
            "username" => "required|email|max:20",
            "password" => ["required", "min:6", "max:20"]
        ];

        $validator = Validator::make($data, $rules);

        assertTrue($validator->fails());

        Log::info($validator->errors()->toJson(JSON_PRETTY_PRINT));

    }

    public function testValidatedData()
    {

        $data = [
            "username" => "admin",
            "password" => "rahasia"
        ];

        $rules = [
            "username" => "required|email|max:20",
            "password" => ["required", "min:6", "max:20"]
        ];

        $validator = Validator::make($data, $rules);

        try{

            $valid = $validator->validate();
            Log::info(json_encode($valid, JSON_PRETTY_PRINT));
            self::fail("Validation exception not thrown");
        } catch(ValidationException $exception) {

            self::assertNotNull($exception->validator);

            $message = $validator->getMessageBag();

            Log::error($message->toJson(JSON_PRETTY_PRINT));

        }

    }

    public function testValidatorWithCostumizedMessage()
    {

        $data = [
            "username" => "admin",
            "password" => "rahasia"
        ];

        $rules = [
            "username" => "required|email|max:20",
            "password" => ["required", "min:6", "max:20"]
        ];

        $messages = [
            'email' => 'Woi Email Dungu',
        ];

        $validator = Validator::make($data, $rules, $messages);

        try{

            $valid = $validator->validate();
            Log::info(json_encode($valid, JSON_PRETTY_PRINT));
            self::fail("Validation exception not thrown");
        } catch(ValidationException $exception) {

            self::assertNotNull($exception->validator);

            $message = $validator->getMessageBag();

            Log::error($message->toJson(JSON_PRETTY_PRINT));

        }

    }

    public function testAddingValidation()
    {

        $data = [
            "username" => "rahasia",
            "password" => "rahasia"
        ];

        $rules = [
            "username" => "required|max:20",
            "password" => ["required", "min:6", "max:20"]
        ];

        $validator = Validator::make($data, $rules);

        $validator->after(function (\Illuminate\Validation\Validator $validator) {
            $data = $validator->getData();
            if($data['username'] == $data['password'])
            {
                $validator->errors()->add('password', 'Password and username cannot be the same');
            }
        });

        self::assertTrue($validator->fails());

        Log::info($validator->errors()->toJson(JSON_PRETTY_PRINT));

    }


}
