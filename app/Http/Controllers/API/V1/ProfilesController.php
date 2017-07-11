<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Activity;
use App\Models\User;

class ProfilesController
{
    public function show(User $user)
    {
        return [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ];
    }

}