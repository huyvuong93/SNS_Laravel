<?php

namespace App\Repositories;

use App\Models\Friend;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PrivateChat;
use App\Models\Notification;
use App\Models\FriendRequest;

class FriendRepository
{
    public function acceptFriendReq(int $sendReqId, int $receiveReqId)
    {
        $data = [];
        $data[0] = [
            'user_id' => $sendReqId,
            'friend_id' => $receiveReqId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $data[1] = [
            'user_id' => $receiveReqId,
            'friend_id' => $sendReqId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        DB::beginTransaction();
        try {
            Friend::insert($data);
            $this->createPrivateChatRoom($sendReqId, $receiveReqId);
            $this->deleteRequest($sendReqId, $receiveReqId);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function createPrivateChatRoom(int $sendReqId, int $receiveReqId)
    {
        $roomId = date('YmdHis') . $sendReqId . $receiveReqId;
        $data = [];
        $data[0] = [
            'room_id' => $roomId,
            'member_id' => $sendReqId,
        ];
        $data[1] = [
            'room_id' => $roomId,
            'member_id' => $receiveReqId,
        ];
        try {
            PrivateChat::insert($data);
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteRequest(int $sendReqId, int $receiveReqId)
    {
        $request = FriendRequest::where('send_req_user_id', $sendReqId)->where('receive_req_user_id', $receiveReqId)->first();
        $notification = Notification::where('user_id', $receiveReqId)->first();
        try {
            $request->delete();
            $notification->delete();
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }
}
