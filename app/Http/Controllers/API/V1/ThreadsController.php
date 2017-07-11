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

    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        return $this->response->paginator($threads, new ThreadTransformer());
    }

    public function show(Channel $channel, Thread $thread)
    {
        $thread = Thread::findOrFail($thread->id);

        return $this->response->item($thread, new ThreadTransformer());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->input(),[
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $request->only('title', 'body','channel_id');
        $attributes['user_id'] = $this->user()->id;

        $thread = $this->thread->create($attributes);


//        $location = dingo_route('v1', 'threads.show', $thread->channel->slug,$thread->id);
        $location = $thread->path();

        return $this->response->created($location);
    }

    public function destroy(Channel $channel, Thread $thread)
    {
        $thread = $this->thread->findOrFail($thread->id);

        if ($thread->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $thread->delete();

        return $this->response->noContent();
    }

    public function update(Request $request, Channel $channel,Thread $thread)
    {
        $thread = $this->thread->findOrFail($thread->id);

        if ($thread->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $validator = Validator::make($request->input(),[
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

        if($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate();
    }
}

