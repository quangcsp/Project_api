<?php

namespace App\Events;

use App\Eloquent\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationHandler implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $messages;
    protected $user_id;
    protected $action;

    public function __construct($messages,$user_id,$action)
    {
        $this->messages = $messages;
        $this->user_id = $user_id;
        $this->action = $action;
    }

    public  function broadcastOn()
    {
        return new Channel('channel_notification');
    }

    public function broadcastWith()
    {
        return [
            'messages' => $this->messages,
            'user_id' => $this->user_id,
            'action' => $this->action,
        ];
    }
}
