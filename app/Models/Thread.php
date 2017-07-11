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

    //Other
    public function path()
    {
        return "/api/threads/{$this->channel->slug}/{$this->id}";
    }

}
