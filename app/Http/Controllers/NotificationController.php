<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Inertia\Inertia;

class NotificationController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $user = $this->userRepository->getCurrentUser();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();
        $user->notifications()->update([
            'read_at' => Carbon::now()
        ]);
        return Inertia::render('Notification', [
                'notifications' => $notifications
            ]);
    }
}
