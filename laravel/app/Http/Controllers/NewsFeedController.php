<?php

namespace App\Http\Controllers;

use App\Models\Enum\PostStatus;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class NewsFeedController extends Controller
{
    protected UserRepository $userRepository;
    protected PostRepository $postRepository;

    public function __construct(UserRepository $userRepository, PostRepository $postRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
//        dd($request->user()->loadCount(['unreadNotifications']));
        $user = $this->userRepository->getCurrentUser();
        $followingIds = $user->followings()->pluck('following_id');
        $posts = $this->postRepository->getMyNewsFeed($user);
        $status = PostStatus::getValue();
        $likedPostIds = Arr::flatten($user->likedPostIds()->get()->toArray());
        $likedCommentIds = Arr::flatten($user->likedCommentIds()->get()->toArray());

        if ($request->wantsJson()) {
            return $posts;
        }

        return Inertia::render('Index', [
            'user' => $user,
            'followingIds' =>$followingIds,
            'posts' => $posts,
            'status' => $status,
            'likedPostIds' => $likedPostIds,
            'likedCommentIds' => $likedCommentIds,
        ]);
    }

}
