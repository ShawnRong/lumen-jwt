<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\ThreadFilters;
use App\Models\Channel;
use App\Models\Thread;
use App\Transformers\ThreadTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThreadsController extends BaseController
{
    private $thread;
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    /**
     * @api {get} /threads Threads List
     * @apiDescription Threads List
     * @apiGroup Thread
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 OK
     *      {
     *          "data": [
     *              {
     *                  "id": 31,
     *                  "user_id": 1,
     *                  "channel_id": 1,
     *                  "replies_count": 0,
     *                  "title": "active_test",
     *                  "body": "active_test_body",
     *                  "created_at": "2017-07-11 06:22:29"
     *              },
     *              {
     *                  "id": 30,
     *                  "user_id": 1,
     *                  "channel_id": 1,
     *                  "replies_count": 0,
     *                  "title": "active_test",
     *                  "body": "active_test_body",
     *                  "created_at": "2017-07-11 06:21:27"
     *              }
     *          ],
     *          "meta": {
     *              "pagination": {
     *              "total": 28,
     *              "count": 15,
     *              "per_page": 15,
     *              "current_page": 1,
     *              "total_pages": 2,
     *              "links": {
     *              "next": "http://dev-project5.dev/api/threads?page=2"
     *              }
     *          }
     *      }
     *
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        return $this->response->paginator($threads, new ThreadTransformer());
    }

    /**
     * @api {get} /threads/{channel}/{thread} Get thread detail
     * @apiDescription Get Thread Detail
     * @apiGroup Thread
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 OK
     *      {
     *          "data": {
     *              "id": 31,
     *              "user_id": 1,
     *              "channel_id": 1,
     *              "replies_count": 0,
     *              "title": "active_test",
     *              "body": "active_test_body",
     *              "created_at": "2017-07-11 06:22:29"
     *          }
     *      }
     *
     */
    public function show(Channel $channel, Thread $thread)
    {
        $thread = Thread::findOrFail($thread->id);

        return $this->response->item($thread, new ThreadTransformer());
    }

    /**
     * @api {post} /threads Create a Thread
     * @apiDescription Create a Thread
     * @apiGroup Thread
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String}  title title
     * @apiParam {String} body body
     * @apiParam {Int} channel_id channel id
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $request->only('title', 'body', 'channel_id');
        $attributes['user_id'] = $this->user()->id;

        $thread = $this->thread->create($attributes);


//        $location = dingo_route('v1', 'threads.show', $thread->channel->slug,$thread->id);
        $location = $thread->path();

        return $this->response->created($location);
    }


    /**
     * @api {delete} /threads Delete a Thread
     * @apiDescription Delete a Thread
     * @apiGroup Thread
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 204 No Content
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $thread = $this->thread->findOrFail($thread->id);

        if ($thread->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $thread->delete();

        return $this->response->noContent();
    }


    /**
     * @api {put} /threads Update a Thread
     * @apiDescription Update a Thread
     * @apiGroup Thread
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String}  title title
     * @apiParam {String} body body
     * @apiParam {Int} channel_id channel id
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
    public function update(Request $request, Channel $channel, Thread $thread)
    {
        $thread = $this->thread->findOrFail($thread->id);

        if ($thread->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $validator = Validator::make($request->input(), [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $thread->update(['title' => 'update', 'body'=> 'bodyupdate']);

        return $this->response->noContent();
    }

    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate();
    }
}
