<?php

namespace App\Events;

use App\Eloquent\Notification;

class SaveNotificationHandler
{
    public function handle($data)
    {
        app(Notification::class)->create([
            'user_send_id' => $data['current_user_id'],
            'user_receive_id' => $data['get_user_id'],
            'target_id' => $data['target_id'],
            'type' => $data['type'],
        ]);
    }
}
