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
        $this->userRepository = $userRepository;
        $this->friendRepository = $friendRepository;
    }

    public function send(Request $request)
    {
        $user = $this->userRepository->getCurrentUser();
        $reqId = $request->userId;
        $this->userRepository->sendFriendReq($user->id, $reqId);
        return response()->json('true');
    }

    public function accept(Request $request)
    {
        $sendReqId = $request->sendId;
        $receiveReqId = $request->receiveId;
        $this->friendRepository->acceptFriendReq($sendReqId, $receiveReqId);
        return redirect()->to('/');
    }

}
