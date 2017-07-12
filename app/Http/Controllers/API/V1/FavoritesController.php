<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Reply;

class FavoritesController extends BaseController
{

    /**
     * @api {post} /replies/{reply}/favorites Favorite a reply
     * @apiDescription Favorite a reply
     * @apiGroup Favorite
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": "Favorite an item"
     *      }
     */
    public function store(Reply $reply)
    {
        $reply->favorite();

        return [
            'status' => 'Favorite an item'
        ];
    }

    /**
     * @api {delete} /replies/{reply}/favorites
     * @apiDescription Unfavorite a reply
     * @apiGroup Favorite
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": "Unfavorited an item"
     *      }
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        return [
            'status' => 'Unfavorited an item'
        ];
    }
}
