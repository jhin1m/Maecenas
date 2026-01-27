<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Level;


class SendMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;

    public function __construct(User $user, $message)
    {

        $level = Level::where('experience', '<=', $user->exp)->orderBy('experience', 'desc')->first();
        if (!$level) {
            $level = (object) [
                'level' => "Cấp 0",
                'image' => 'default_image_url'
            ];
        }
        $this->user = (object) [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'level' => $level,
            'created_at' => now()->format('H:i'),
        ];;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return ['room-chat'];
    }

    public function broadcastAs()
    {
        return 'chat';
    }
}
