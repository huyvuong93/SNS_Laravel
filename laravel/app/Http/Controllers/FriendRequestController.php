<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\FriendRepository;

class FriendRequestController extends Controller
{
    protected UserRepository $userRepository;
    protected FriendRepository $friendRepository;

    public function __construct(UserRepository $userRepository, FriendRepository $friendRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->friendRepository = $friendRepository;
    }

    public function send(Request $request)
    {
        $user = $this->userRepository->getCurrentUser();
        $reqId = $request->userId;
        $isFriend = $this->friendRepository->checkExistFriend($user, $reqId);
        if ($isFriend) {
           $result = $this->friendRepository->unfriend($user, $isFriend);
        } else {
           $result = $this->friendRepository->sendFriendReq($user, $reqId);
        }
        return response()->json($result);
    }

    public function accept(Request $request)
    {
        $sendReqId = $request->sendId;
        $receiveReqId = $request->receiveId;
        $this->friendRepository->acceptFriendReq($sendReqId, $receiveReqId);
        return redirect()->to('/');
    }

}
