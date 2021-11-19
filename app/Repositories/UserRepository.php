<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\FriendRequest;
use App\Models\Notification;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository
{
    // Query
    public function allUser()
    {
        return User::all();
    }

    public function getUserById(int $id)
    {
        return User::query()->where('id', $id)->first();
    }

    public function getCurrentUser()
    {
        return Auth::user();
    }

    public function getUserByKeywords(?string $keywords)
    {
        $query = User::query();
        if (isset($keywords)) {
            $query->where('name', 'like', $keywords);
        }

        return $query->paginate(2);
    }



    // Action
    public function updateUser($id, $attributes): bool
    {
        $user = $this->getUserById($id);
        try {
            $user->update($attributes);
            return true;
        } catch (\Exception $e) {
            Log::error('Error : ' . $e->getMessage());
            return false;
        }
    }

    public function sendFriendReq(int $sendReqUserId, int $receiveReqUserId)
    {
        try {
            FriendRequest::create([
                'send_req_user_id' => $sendReqUserId,
                'receive_req_user_id' => $receiveReqUserId
            ]);
            $this->createFriendReqNotification($sendReqUserId, $receiveReqUserId);
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function createFriendReqNotification(int $sendReqUserId, int $receiveReqUserId)
    {
        $sendReqUser = $this->getUserById($sendReqUserId);
        $requestContent = view('components.friend_req', compact('sendReqUser', 'sendReqUserId', 'receiveReqUserId'));
        return Notification::create([
            'user_id' => $receiveReqUserId,
            'notification_content' => $requestContent
        ]);
    }
}
