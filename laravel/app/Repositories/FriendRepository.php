<?php

namespace App\Repositories;

use App\Models\Friend;
use App\Models\PrivateChatRoom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PrivateChat;
use App\Models\Notification;
use App\Models\FriendRequest;
use App\Models\FollowingUser;

class FriendRepository
{
    // Query
    public function getUserFriendList($query, $condition)
    {
        $friendIds = $query->pluck('friend_id')->toArray();
        $friends = User::whereIn('id', $friendIds);
        if (isset($condition)) {
            $friends->where('name', 'like', '%' . $condition . '%');
        }
        return $friends->paginate(20);
    }

    public function checkExistFriend($user, int $reqId)
    {
        return $user->friends()->where('friend_id', $reqId)->first();
    }



    // Action

    public function sendFriendReq($user, int $receiveReqUserId)
    {
        try {
            FriendRequest::create([
                'send_req_user_id' => $user->id,
                'receive_req_user_id' => $receiveReqUserId
            ]);
            $this->createFriendReqNotification($user, $receiveReqUserId);
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function createFriendReqNotification($user, int $receiveReqUserId)
    {
        $sendReqUser = $user;
        $requestContent = view('components.friend_req', compact('sendReqUser', 'receiveReqUserId'));
        return Notification::create([
            'user_id' => $receiveReqUserId,
            'sender_id' => $sendReqUser->id,
            'notification_content' => $requestContent
        ]);
    }

    public function acceptFriendReq(int $sendReqId, int $receiveReqId)
    {
        $frData = [];
        $frData[0] = [
            'user_id' => $sendReqId,
            'friend_id' => $receiveReqId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $frData[1] = [
            'user_id' => $receiveReqId,
            'friend_id' => $sendReqId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        DB::beginTransaction();
        try {
            Friend::insert($frData);
            $this->addFollowingUser($sendReqId, $receiveReqId);
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

    public function addFollowingUser(int $sendReqId, int $receiveReqId)
    {
        $followData = [];
        $followData[0] = [
            'user_id' => $sendReqId,
            'following_id' => $receiveReqId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $followData[1] = [
            'user_id' => $receiveReqId,
            'following_id' => $sendReqId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        try {
            FollowingUser::insert($followData);
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function createPrivateChatRoom(int $sendReqId, int $receiveReqId)
    {
        $roomId = date('YmdHis') . $sendReqId . $receiveReqId;
        $data = [];
        $data[0] = [
            'user_id' => $sendReqId,
            'room_id' => $roomId,
            'member_id' => $receiveReqId,
        ];
        $data[1] = [
            'user_id' => $receiveReqId,
            'room_id' => $roomId,
            'member_id' => $sendReqId,
        ];
        try {
            PrivateChatRoom::create([
                'room_id' => $roomId,
            ]);
            PrivateChat::insert($data);
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function unfriend($user, $friend)
    {
        try {
            $friend->delete();
            $conversation = PrivateChat::where('user_id', $user->id)->where('member_id', $friend->id)->first();
            PrivateChatRoom::where('room_id', $conversation->room_id)->delete();
            return 'unfriend';
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
