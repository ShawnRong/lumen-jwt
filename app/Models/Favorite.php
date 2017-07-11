<?php

namespace App\Models;

use App\RecordsActivity;

class Favorite extends BaseModel
{
    use RecordsActivity;

    public function favorited()
    {
        return $this->morphTo();
    }
}