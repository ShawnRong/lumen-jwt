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
    /**
     * @api {get} /users Get all users
     * @apiDescription Get all users
     * @apiGroup User
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 OK
     *     {
     *          "data": [
     *          {
     *              "id": 1,
     *              "name": "adminnew",
     *              "email": "admin@123.com",
     *              "created_at": "2017-05-18 19:27:40",
     *              "updated_at": "2017-06-29 05:58:27"
     *          },
     *          {
     *              "id": 2,
     *              "name": "admin321",
     *              "email": "admin@321.com",
     *              "created_at": "2017-06-29 03:07:00",
     *              "updated_at": "2017-06-29 03:07:00"
     *          }
     *          ],
     *          "meta": {
     *              "pagination": {
     *              "total": 2,
     *              "count": 2,
     *              "per_page": 15,
     *              "current_page": 1,
     *              "total_pages": 1,
     *              "links": []
     *          }
     *          }
     *      }
     */
    public function index(User $user)
    {
        $users = User::paginate();

        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * @api {get} /users/{id} Get one user info
     * @apiDescription Get one user info
     * @apiGroup User
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 OK
     *
     *      {
     *          "data": {
     *          "id": 2,
     *          "name": "admin321",
     *          "email": "admin@321.com",
     *          "created_at": "2017-06-29 03:07:00",
     *          "updated_at": "2017-06-29 03:07:00"
     *          }
     *      }
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * @api {get} /user Get current user info
     * @apiDescription Get current user info
     * @apiGroup User
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 OK
     *      {
     *          "data": {
     *          "id": 1,
     *          "name": "adminnew",
     *          "email": "admin@123.com",
     *          "created_at": "2017-05-18 19:27:40",
     *          "updated_at": "2017-06-29 05:58:27"
     *          }
     *      }
     */
    public function userShow()
    {
        return $this->response->item($this->user, new UserTransformer());
    }

    /**
     * @api {post} /users Create a user
     * @apiDescription Create a user
     * @apiGroup User
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiParam {Email}  email   email[unique]
     * @apiParam {String} password   password
     * @apiParam {String} name      name
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "data": {
     *          "email": "test@1234.com",
     *          "name": "testUsers",
     *          "updated_at": "2017-07-12 03:51:12",
     *          "created_at": "2017-07-12 03:51:12",
     *          "id": 4,
     *          "authorization": {
     *              "data": {
     *                  "id": "c82fe531f6e417b75ed7065868128615",
     *                  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vZGV2LXByb2plY3Q1LmRldi9hcGkvdXNlcnMiLCJpYXQiOjE0OTk4MzE0NzIsImV4cCI6MTQ5OTgzNTA3MiwibmJmIjoxNDk5ODMxNDcyLCJqdGkiOiJvV1JFRkV1REYxdUhPNU1DIiwic3ViIjo0fQ.NlU5hjKVj4T4EBnIkTLhz3SMTFiVfbMjEmldoUY0Jkc",
     *                  "expired_at": "2017-07-12 04:51:12",
     *                  "refresh_expired_at": "2017-07-26 03:51:12"
     *              }
     *              }
     *          }
     *      }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 422 Unprocessable Entity
     *      {
     *          "message": "422 Unprocessable Entity",
     *          "errors": [
     *              {
     *                  "field": "email",
     *                  "code": "The email has already been taken."
     *              }
     *          ]
     *      }
     */
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

    /**
     * @api {patch} /user Update current user info
     * @apiDescription Update current user info
     * @apiGroup User
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} [name] name
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "data": {
     *              "id": 1,
     *              "name": "testUsers",
     *              "email": "admin@123.com",
     *              "created_at": "2017-05-18 19:27:40",
     *              "updated_at": "2017-07-12 03:56:21"
     *          }
     *      }
     */
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

    /**
     * @api {put} /user/password Edit password
     * @apiDescription Edit password
     * @apiGroup User
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} old_password          Old password
     * @apiParam {String} password              New password
     * @apiParam {String} password_confirmation Confirmation password
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 204 No Content
     */
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
