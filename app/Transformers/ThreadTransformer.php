<?php

namespace App\Transformers;

use App\Models\Thread;
use League\Fractal\TransformerAbstract;

class ThreadTransformer extends TransformerAbstract
{
    public function transform(Thread $thread)
    {
        return $thread->attributesToArray();
    }
}
