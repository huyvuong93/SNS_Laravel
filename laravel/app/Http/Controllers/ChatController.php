<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\PrivateChatMessageRepository;
use Inertia\Inertia;

class ChatController extends Controller
{
    protected UserRepository $userRepository;
    protected PrivateChatMessageRepository $privateChatMessageRepository;

    public function __construct(UserRepository $userRepository, PrivateChatMessageRepository $privateChatMessageRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->privateChatMessageRepository = $privateChatMessageRepository;
    }

    public function index()
    {
        $user = $this->userRepository->getCurrentUser();
        $chatList = $this->privateChatMessageRepository->getChatList($user);
        return Inertia::render('Chat', [
            'user' => $user,
            'chatList' => $chatList->map(function ($member) {
                $user = $this->userRepository->getUserById($member->member_id);
                $user->is_online = $user->isOnline();
                return [
                    'id' => $member->member_id,
                    'roomId' => $member->room_id,
                    'user' => $user,
                ];
            }),
        ]);
    }

    public function show(Request $request)
    {
        $roomId = $request->roomId;
        $user = $this->userRepository->getCurrentUser();
        $chatList = $this->privateChatMessageRepository->getChatList($user);
        $privateChat = $this->privateChatMessageRepository->getPrivateChat($roomId);
        return Inertia::render('PrivateChat', [
            'roomId' => $roomId,
            'user' => $user,
            'chatList' => $chatList->map(function ($member) {
                return [
                    'id' => $member->member_id,
                    'roomId' => $member->room_id,
                    'user' => $this->userRepository->getUserById($member->member_id),
                ];
            }),
            'privateChat' => $privateChat->map(function ($message) {
                return [
                    'sender_id' => $message->send_mes_user_id,
                    'room_id' => $message->room_id,
                    'name' => $message->user->name,
                    'content' => $message->message,
                    'created_at' => Carbon::parse($message->created_at)->diffForHumans(),
                ];
            })
        ]);
    }

    public function create(Request $request)
    {
        $user = $this->userRepository->getCurrentUser();
        $roomId = $request->roomId;
        $message = $request->message;
        $data = $this->privateChatMessageRepository->createMessage($user->id, $message, $roomId);
        broadcast(new ChatEvent($user, $data))->toOthers();
        return ['status' => 'Sent'];
    }

    /**
     * @param int|null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function retrieveMessages(?int $id)
    {
        if ($id === null) {
            $privateChat = [];
        } else {
            $data = $this->privateChatMessageRepository->getPrivateChat($id);
            $privateChat = $data->map(function ($message) {
                return [
                    'sender_id' => $message->send_mes_user_id,
                    'room_id' => $message->room_id,
                    'name' => $message->user->name,
                    'content' => $message->message,
                    'created_at' => Carbon::parse($message->created_at)->diffForHumans(),
                ];
            });
        }
        return response()->json($privateChat);
    }
}
