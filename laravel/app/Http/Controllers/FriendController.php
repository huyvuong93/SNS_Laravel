<?php

namespace App\Http\Controllers;

use App\Models\Enum\PostStatus;
use App\Models\Friend;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use App\Repositories\FriendRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class FriendController extends Controller
{
    protected UserRepository $userRepository;
    protected PostRepository $postRepository;
    protected FriendRepository $friendRepository;

    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        FriendRepository $friendRepository
    ) {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->friendRepository = $friendRepository;
    }

    public function index(Request $request)
    {
        $user = $this->userRepository->getCurrentUser();
        $friends = $this->friendRepository->getUserFriendList($user->friends(), $request->filter);

        return Inertia::render('Friends/Index', [
            'friends' => $friends,
            'filter_data' => $request->filter,
        ]);
    }

    public function show(Request $request)
    {
        $user = $this->userRepository->getUserById($request->id);
        $posts = $user->posts()->get();
        $status = PostStatus::getValue();
        $likedPostIds = Arr::flatten($user->likedPostIds()->get()->toArray());
        $likedCommentIds = Arr::flatten($user->likedCommentIds()->get()->toArray());

        if ($request->wantsJson()) {
            return response()->json($posts);
        }

        return Inertia::render('Friends/Profile', [
            'user' => $user,
            'posts' => $posts,
            'status' => $status,
            'likedPostIds' => $likedPostIds,
            'likedCommentIds' => $likedCommentIds,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Friend $friend
     * @return \Illuminate\Http\Response
     */
    public function edit(Friend $friend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Friend $friend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Friend $friend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Friend $friend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Friend $friend)
    {
        //
    }
}
