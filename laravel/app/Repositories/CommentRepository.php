<?php


namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class CommentRepository
{
    // Query

    public function getCommentByPostId(int $id)
    {
       return Comment::where('post_id', $id)->withCount('likes')->orderBy('likes_count', 'desc')->paginate(5);
    }


    // Action

    public function comment(int $userId, int $postId, $content)
    {
        try {
            $comment = Comment::create([
                'user_id' => $userId,
                'post_id' => $postId,
                'content' => $content,
            ]);
            return $comment;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }
}
