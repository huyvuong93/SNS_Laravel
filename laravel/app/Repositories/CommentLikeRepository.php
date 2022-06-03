<?php


namespace App\Repositories;

use App\Models\CommentLike;
use Illuminate\Support\Facades\Log;

class CommentLikeRepository
{
    // Query

    public function checkCommentLike(int $commentId, int $userId)
    {
        return CommentLike::where('comment_id', $commentId)->where('user_id', $userId)->first();
    }

    public function getCommentLikeCount(int $commentId)
    {
        return CommentLike::where('comment_id', $commentId)->count();
    }

    // Action

    public function commentLikeAction(int $commentId, int $userId)
    {
        $commentLike = $this->checkCommentLike($commentId, $userId);
        if ($commentLike) {
            $this->unlike($commentLike);
            $status = 'unlike';
        } else {
            $this->like($commentId, $userId);
            $status = 'like';
        }
        return [
            'status' => $status,
            'count' => $this->getCommentLikeCount($commentId)
        ];
    }

    public function unlike(CommentLike $commentLike)
    {
        try {
            $commentLike->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function like(int $commentId, int $userId)
    {
        try {
            CommentLike::create([
                'comment_id' => $commentId,
                'user_id' => $userId,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }
}
