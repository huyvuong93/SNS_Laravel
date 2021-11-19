<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Models\Enum\PostStatus;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class HomeController extends Controller
{
    protected UserRepository $userRepository;
    protected PostRepository $postRepository;

    public function __construct(UserRepository $userRepository, PostRepository $postRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        $user = $this->userRepository->getCurrentUser();
        $posts = $user->posts()->get();
        $status = PostStatus::getValue();
        $likedPostIds = Arr::flatten($user->likedPostIds()->get()->toArray());
        $likedCommentIds = Arr::flatten($user->likedCommentIds()->get()->toArray());

        if ($request->wantsJson()) {
            return response()->json($posts);
        }

        return Inertia::render('MyPage/Index', [
            'user' => $user,
            'posts' => $posts,
            'status' => $status,
            'likedPostIds' => $likedPostIds,
            'likedCommentIds' => $likedCommentIds,
        ]);
    }
}
