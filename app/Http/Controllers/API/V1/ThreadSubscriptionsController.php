<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Channel;
use App\Models\Thread;

class ThreadSubscriptionsController extends BaseController
{

    /**
     * @api {post} threads/{channel}/{thread}/subscriptions Subscribe a thread
     * @apiDescription Subscribe a thread
     * @apiGroup Subscribe
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": "Subscribe thread success"
     *      }
     */
    public function store(Channel $channel, Thread $thread)
    {
        $thread->subscribe();

        return [
            'status' => 'Subscribe thread success'
        ];
    }

    /**
     * @api {delete} threads/{channel}/{thread}/subscriptions Unsubscribe a thread
     * @apiDescription Unsubscribe a thread
     * @apiGroup Subscribe
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": "Unsubscribe success"
     *      }
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $thread->unsubscribe();

        return [
            'status' => 'Unsubscribe thread'
        ];
    }
}
