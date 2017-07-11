<?php

namespace App\Models;

use App\RecordsActivity;

class Thread extends BaseModel
{
    use RecordsActivity;

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filter)
    {
        return $filter->apply($query);
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => app('Dingo\Api\Auth\Auth')->user()->id,
        ]);

        return $this;
    }

    public function unsubscribe($useId = null)
    {
        $this->subscriptions()->where('user_id', $useId ? :app('Dingo\Api\Auth\Auth')->user()->id)->delete();
    }

    //Other
    public function path()
    {
        return "/api/threads/{$this->channel->slug}/{$this->id}";
    }
}
