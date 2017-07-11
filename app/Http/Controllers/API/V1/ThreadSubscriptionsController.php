<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Channel;
use App\Models\Thread;

class ThreadSubscriptionsController extends BaseController
{
    public function store(Channel $channel, Thread $thread)
    {
        $thread->subscribe();

        return [
            'status' => 'Subscribe thread success'
        ];
    }

    public function destroy(Channel $channel, Thread $thread)
    {
        $thread->unsubscribe();

        return [
            'status' => 'Unsubscribe thread'
        ];
    }
}