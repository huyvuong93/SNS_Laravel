<?php

namespace App\Repositories;

use App\Models\PrivateChatMessage;
use App\Models\PrivateChat;

class PrivateChatMessageRepository
{
    public function createMessage(int $senderId, string $message, $roomId)
    {
        return PrivateChatMessage::create([
            'room_id' => $roomId,
            'send_mes_user_id' => $senderId,
            'message' => $message
        ]);
    }

    public function getChatList($user)
    {
        $friendIds = $user->friends()->pluck('friend_id');
        return PrivateChat::whereIn('member_id', $friendIds)->get();
    }

    public function getPrivateChat($roomId)
    {
        return PrivateChatMessage::where('room_id', $roomId)->with('user')->get();
    }
}
