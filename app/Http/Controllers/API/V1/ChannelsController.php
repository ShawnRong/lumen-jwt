<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Channel;
use App\Transformers\ChannelTransformer;

class ChannelsController extends BaseController
{
    private $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * @api {get} /channels Channels List
     * @apiDescription Channels List
     * @apiGroup Channel
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 OK
     *      {
     *          "channels": [
     *              {
     *              "id": 1,
     *              "name": "illum",
     *              "slug": "illum",
     *              "created_at": "2017-06-29 09:32:07"
     *              },
     *              {
     *              "id": 2,
     *              "name": "veniam",
     *              "slug": "veniam",
     *              "created_at": "2017-06-29 09:32:07"
     *              }
     *          ]
     *      }
     *
     */
    public function index()
    {
        $this->channel = Channel::all();
        return $this->response->item($this->channel, new ChannelTransformer());
    }
}
