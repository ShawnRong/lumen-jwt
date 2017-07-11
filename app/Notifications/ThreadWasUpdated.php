<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Notification;

class ThreadWasUpdated extends Notification
{
    protected $thread;

    protected $reply;

    public function __construct($thread, $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

    public function via()
    {
        return ['database'];
    }

    public function toArray()
    {
        //TODO: Add thread create notification
    }
}
