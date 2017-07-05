<?php

namespace App\Http\Controllers\Api\V1;

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

    public function index()
    {
        $threads = $this->thread->paginate();

        return $this->response->paginator($threads, new ThreadTransformer());

    }

    public function show($threadId)
    {
        $thread = Thread::findOrFail($threadId);

        return $this->response->item($thread, new ThreadTransformer());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->input(),[
            'title' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $request->only('title', 'body');
        $attributes['user_id'] = $this->user()->id;

        $thread = $this->thread->create($attributes);

        $location = dingo_route('v1', 'threads.show', $thread->id);

        return $this->response->created($location);
    }

    public function destroy($threadId)
    {
        $thread = $this->thread->findOrFail($threadId);

        if ($thread->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $thread->delete();

        return $this->response->noContent();
    }

    public function update(Request $request, $threadId)
    {
        $thread = $this->thread->findOrFail($threadId);

        if ($thread->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $validator = Validator::make($request->input(),[
            'title' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $thread->update(['title' => 'update', 'body'=> 'bodyupdate']);

        return $this->response->noContent();
    }
}

