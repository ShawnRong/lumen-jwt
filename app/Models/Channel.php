<?php

namespace App\Models;

class Channel extends BaseModel
{
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

}

