<?php


namespace App\Repositories;

use App\Models\PostLike;
use Illuminate\Support\Facades\Log;

class PostLikeRepository
{
    // Query

    public function checkPostLike(int $postId, int $userId)
    {
        return PostLike::where('post_id', $postId)->where('user_id', $userId)->first();
    }

    public function getPostLikeCount(int $postId)
    {
        return PostLike::where('post_id', $postId)->count();
    }

    // Action

    public function postLikeAction(int $postId, int $userId)
    {
        $postLike = $this->checkPostLike($postId, $userId);
        if ($postLike) {
            $this->unlike($postLike);
            $status = 'unlike';
        } else {
            $this->like($postId, $userId);
            $status = 'like';
        }
        return [
            'status' => $status,
            'count' => $this->getPostLikeCount($postId)
        ];
    }

    public function unlike(PostLike $postLike)
    {
        try {
            $postLike->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function like(int $postId, int $userId)
    {
        try {
            PostLike::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

}
