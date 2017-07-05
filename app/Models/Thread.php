<?php

namespace App\Models;

class Thread extends BaseModel
{
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
