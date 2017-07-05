<?php

namespace App\Http\Controllers\Api\V1;

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

    public function index($threadId)
    {
        $thread = $this->thread->findOrFail($threadId);
        return $thread->replies()->paginate(2);

    }

    public function store(Request $request, $threadId)
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
        $attributes['thread_id'] = $threadId;

        $this->reply->create($attributes);

        return $this->response->created();
    }

    public function destroy($replyId)
    {
        $reply = $this->reply->findOrFail($replyId);

        if ($reply->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $reply->delete();

        return $this->response->noContent();
    }

    public function update(Request $request, $replyId)
    {
        $reply = $this->reply->findOrFail($replyId);

        if ($reply->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $validator = Validator::make($request->input(),[
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $reply->update(['body'=> 'bodyupdate']);

        return $this->response->noContent();
    }

}