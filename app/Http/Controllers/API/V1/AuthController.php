<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Authorization;
use App\Transformers\AuthorizationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //handle error
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        //get email and password from request
        $credentials = $request->only('email', 'password');

        //validate token
        if (! $token = Auth::attempt($credentials)) {
            $this->response->errorUnauthorized('incorrect');
        }

        $authorization = new Authorization($token);

        return $this->response->item($authorization, new AuthorizationTransformer())
            ->setStatusCode(201);
    }

    public function update()
    {
        $authorization = new Authorization(Auth::refresh());

        return $this->response->item($authorization, new AuthorizationTransformer());
    }

    public function destroy()
    {
        Auth::logout();

        return $this->response->noContent();
    }
}
