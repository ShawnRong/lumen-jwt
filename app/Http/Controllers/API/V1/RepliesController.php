<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Validator;

class RepliesController extends BaseController
{
    protected $reply;
    protected $thread;

    public function __construct(Reply $reply, Thread $thread)
    {
        $this->reply = $reply;
        $this->thread = $thread;
    }


    /**
     * @api {get} /threads/{channel}/{thread}/replies Replies List
     * @apiDescription Replies List
     * @apiGroup Reply
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 OK
     *      {
     *          "current_page": 1,
     *          "data": [
     *              {
     *                  "id": 1,
     *                  "thread_id": 6,
     *                  "user_id": 1,
     *                  "body": "bodyupdate",
     *                  "created_at": "2017-07-05 06:39:57"
     *              },
     *              {
     *                  "id": 2,
     *                  "thread_id": 6,
     *                  "user_id": 1,
     *                  "body": "repliesonessds",
     *                  "created_at": "2017-07-05 06:40:19"
     *              }
     *          ],
     *          "from": 1,
     *          "last_page": 1,
     *          "next_page_url": null,
     *          "path": "http://dev-project5.dev/api/threads/illum/6/replies",
     *          "per_page": 20,
     *          "prev_page_url": null,
     *          "to": 2,
     *          "total": 2
     *      }
     */
    public function index(Channel $channel, Thread $thread)
    {
        $thread = $this->thread->findOrFail($thread->id);
        return $thread->replies()->paginate(20);
    }

    /**
     * @api {post} /threads/{channel}/{thread}/replies Create a Reply
     * @apiDescription Create a Reply
     * @apiGroup Reply
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} body body
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 Created
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 422 Unprocessable Entity
     *      {
     *          "message": "422 Unprocessable Entity",
     *          "errors": [
     *              {
     *                  "field": "body",
     *                  "code": "The body field is required."
     *              }
     *          ]
     *      }
     */
    public function store(Request $request, Thread $thread)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $user = $this->user()->id;

        $attributes = $request->only('body');
        $attributes['user_id'] = $user;
        $attributes['thread_id'] = $thread->id;

        $this->reply->create($attributes);

        return $this->response->created();
    }

    /**
     * @api {delete} /replies/{reply} Delete a Reply
     * @apiDescription Delete a Reply
     * @apiGroup Reply
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 204 No Content
     */
    public function destroy(Reply $reply)
    {
        $reply = $this->reply->findOrFail($reply->id);

        if ($reply->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $reply->delete();

        return $this->response->noContent();
    }

    /**
     * @api {patch} /replies/{reply} Update a Reply
     * @apiDescription Update a Reply
     * @apiGroup Reply
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} body body
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 204 No Content
     *
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 422 Unprocessable Entity
     *      {
     *          "message": "422 Unprocessable Entity",
     *          "errors": [
     *              {
     *                  "field": "body",
     *                  "code": "The body field is required."
     *              }
     *          ]
     *      }
     */
    public function update(Request $request, Reply $reply)
    {
        $reply = $this->reply->findOrFail($reply->id);

        if ($reply->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $validator = Validator::make($request->input(), [
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $reply->update(['body'=> $request['body']]);

        return $this->response->noContent();
    }
}
