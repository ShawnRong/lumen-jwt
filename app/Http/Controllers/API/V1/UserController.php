<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Authorization;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function index(User $user)
    {
        $users = User::paginate();

        return $this->response->paginator($users, new UserTransformer());
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return $this->response->item($user, new UserTransformer());
    }

    public function userShow()
    {
        return $this->response->item($this->user, new UserTransformer());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'email' => 'required|email|unique:users',
            'name' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $email = $request->get('email');
        $password = $request->get('password');

        $attributes = [
            'email' => $email,
            'name' => $request->get('name'),
            'password' => app('hash')->make($password)
        ];

        $user = User::create($attributes);

        //TODO: send mail after registion

        $location = dingo_route('v1', 'users.show', $user->id);

        //return default token
        $authorization = new Authorization(Auth::fromUser($user));
        $transformer = new UserTransformer();
        $transformer->setAuthorization($authorization)
            ->setDefaultIncludes(['authorization']);

        return $this->response->item($user, $transformer)
            ->header('Location', $location)
            ->setStatusCode(201);
    }

    public function patch(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'name' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $user = $this->user();
        $attributes = array_filter($request->only('name', 'avatar'));

        if ($attributes) {
            $user->update($attributes);
        }

        return $this->response->item($user, new UserTransformer());
    }


    public function editPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|different:old_password',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $user = $this->user();

        $auth = Auth::once([
            'email' => $user->email,
            'password' => $request->get('old_password'),
        ]);

        if (! $auth) {
            return $this->response->errorUnauthorized();
        }

        $password = app('hash')->make($request->get('password'));
        $user->update(['password' => $password]);

        return $this->response->noContent();
    }
}
