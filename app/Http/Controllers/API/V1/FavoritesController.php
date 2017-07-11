<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Reply;

class FavoritesController extends BaseController
{
    public function store(Reply $reply)
    {
        $reply->favorite();

        return [
            'status' => 'Favorite an item'
        ];
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        return [
            'status' => 'Unfavorited an item'
        ];
    }
}
