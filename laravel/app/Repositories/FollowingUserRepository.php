<?php


namespace App\Repositories;

use App\Models\FollowingUser;
use Illuminate\Support\Facades\Log;

class FollowingUserRepository
{
    // Query
    public function checkId(int $userId, int $followId)
    {
        return FollowingUser::where('user_id', $userId)->where('following_id', $followId)->first();
    }


    // Action
    public function follow(int $userId, int $followId)
    {
        try {
            FollowingUser::create([
                'user_id' => $userId,
                'following_id' => $followId
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function unfollow($data)
    {
        try {
            $data->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }
}
