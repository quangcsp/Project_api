<?php

namespace App\Events;

use App\Eloquent\Notification;
use Pusher;

class NotificationAndroidHander
{

    protected $action;

    public function handle($action)
    {
        $this->action = $action;
        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_CLUSTER')]);
            $pusher->notify(
                ['keyChannelFcmAndroid'],
                    [
                    'fcm' => [
                        'notification' => [
                            'title' => $this->action,
                        ],
                    ],
                ]
            );
    }
}
