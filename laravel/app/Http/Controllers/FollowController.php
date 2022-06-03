<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\FollowingUserRepository;

class FollowController extends Controller
{
    protected UserRepository $userRepository;
    protected FollowingUserRepository $followingUserRepository;

    public function __construct(UserRepository $userRepository, FollowingUserRepository $followingUserRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->followingUserRepository = $followingUserRepository;
    }

    public function follow(Request $request)
    {
        $user = $this->userRepository->getCurrentUser();
        $followId = $request->userId;
        $checkId = $this->followingUserRepository->checkId($user->id, $followId);
        if ($checkId) {
            $result = $this->followingUserRepository->unfollow($checkId);
        } else {
            $result = $this->followingUserRepository->follow($user->id, $followId);
        }
        return response()->json($result);
    }

    public function getFollowingUsers()
    {
        $user = $this->userRepository->getCurrentUser();
        $followingIds = $user->followings()->pluck('following_id');
        return response()->json($followingIds);
    }
}
