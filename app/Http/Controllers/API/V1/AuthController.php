<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Authorization;
use App\Transformers\AuthorizationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * @api {post} /authorizations Create a Token
     * @apiDescription Create a token
     * @apiGroup Auth
     * @apiPermission none
     * @apiParam {Email} email email address
     * @apiParam {String} password password
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 Created
     *     {
     *          "data": {
     *          "id": "8b50372fd85f823c777cbccb29103776",
     *          "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vZGV2LXByb2plY3Q1LmRldi9hcGkvYXV0aG9yaXphdGlvbnMiLCJpYXQiOjE0OTk4MjgyMjIsImV4cCI6MTQ5OTgzMTgyMiwibmJmIjoxNDk5ODI4MjIyLCJqdGkiOiJMbG8yR2dmRExyQ2l6N3pnIiwic3ViIjoxfQ.tSqYXGzbvUVOeDpN4Dx7x1lrSppGtXfFkg2S5Hhy4Dc",
     *          "expired_at": "2017-07-12 03:57:02",
     *          "refresh_expired_at": "2017-07-26 02:57:02"
     *          }
     *     }
     */
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

    /**
     * @api {put} /authorizations/current Refresh a token
     * @apiDescription Refresh Token
     * @apiGroup Auth
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiHeader {String} Authorization jwt-token
     * @apiHeaderExample {json} Header-Example:
     *      {
     *          "Authorization":"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vZGV2LXByb2plY3Q1LmRldi9hcGkvYXV0aG9yaXphdGlvbnMiLCJpYXQiOjE0OTk4Mjk0NzIsImV4cCI6MTQ5OTgzMzA3MiwibmJmIjoxNDk5ODI5NDcyLCJqdGkiOiJ5aVlTZVZrM2N0TFRMZG5oIiwic3ViIjoxfQ.TjQ4myEUA3-tfvckQfkk_hdI77uGt5W_ZqAOCLUWxmQ"
     *      }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 OK
     *      {
     *          "data": {
     *          "id": "6a3579d63fc542691720271ed94fff28",
     *          "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vZGV2LXByb2plY3Q1LmRldi9hcGkvYXV0aG9yaXphdGlvbnMvY3VycmVudCIsImlhdCI6MTQ5OTgyOTQ3MiwiZXhwIjoxNDk5ODMzMDg3LCJuYmYiOjE0OTk4Mjk0ODcsImp0aSI6IkJHaGUzRlFab2JMd0NNZnIiLCJzdWIiOjF9.GxYNCHoKHFBniGgFj_KgD3Ntq0kBi85VR6c6GpxiD5o",
     *          "expired_at": "2017-07-12 04:18:07",
     *          "refresh_expired_at": "2017-07-26 03:17:52"
     *          }
     *      }
     */
    public function update()
    {
        $authorization = new Authorization(Auth::refresh());

        return $this->response->item($authorization, new AuthorizationTransformer());
    }

    /**
     * @api {delete} /authorizations/current Delete a token
     * @apiDescription Delete Token
     * @apiGroup Auth
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiHeader {String} Authorization jwt-token
     * @apiHeaderExample {json} Header-Example:
     *      {
     *          "Authorization":"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vZGV2LXByb2plY3Q1LmRldi9hcGkvYXV0aG9yaXphdGlvbnMiLCJpYXQiOjE0OTk4Mjk0NzIsImV4cCI6MTQ5OTgzMzA3MiwibmJmIjoxNDk5ODI5NDcyLCJqdGkiOiJ5aVlTZVZrM2N0TFRMZG5oIiwic3ViIjoxfQ.TjQ4myEUA3-tfvckQfkk_hdI77uGt5W_ZqAOCLUWxmQ"
     *      }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 204 No Content
     *
     */
    public function destroy()
    {
        Auth::logout();

        return $this->response->noContent();
    }
}
