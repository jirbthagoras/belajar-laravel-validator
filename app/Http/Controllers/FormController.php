<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FormController extends Controller
{
    public function login(\Illuminate\Http\Request $request)
    {

        try {

            $data = $request->validate([
                "username" => "required",
                "password" => "required",
            ]);

            return response("OK", Response::HTTP_OK);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return response($exception->errors(), Response::HTTP_BAD_REQUEST);
        }

    }
}
