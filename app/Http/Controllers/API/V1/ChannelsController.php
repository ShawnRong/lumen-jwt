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

    public function index()
    {
        $this->channel = Channel::all();
        return $this->response->item($this->channel, new ChannelTransformer());
    }
}

