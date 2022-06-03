<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\PostLikeRepository;
use App\Repositories\CommentLikeRepository;

class LikeController extends Controller
{
    protected UserRepository $userRepository;
    protected PostLikeRepository $postLikeRepository;
    protected CommentLikeRepository $commentLikeRepository;

    public function __construct(
        UserRepository $userRepository,
        PostLikeRepository $postLikeRepository,
        CommentLikeRepository $commentLikeRepository
    ) {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->postLikeRepository = $postLikeRepository;
        $this->commentLikeRepository = $commentLikeRepository;
    }

    public function like(Request $request)
    {
        $id = $request->id;
        $likeType = $request->type;
        $user = $this->userRepository->getCurrentUser();
        $result = [];
        if ($likeType === 'post_like') {
            $result = $this->postLikeRepository->postLikeAction($id, $user->id);
        } elseif ($likeType === 'comment_like') {
            $result = $this->commentLikeRepository->commentLikeAction($id, $user->id);
        }
        return response()->json([
            'likeType' => $likeType,
            'result' => [
                'status' => $result['status'],
                'count' => $result['count']
            ],
        ]);
    }
}
