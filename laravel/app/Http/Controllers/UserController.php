<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Inertia\Inertia;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware(['auth']);
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $user = $this->userRepository->getUserById($request->id);
        return Inertia::render('Users/Index', [
            'user' => $user
        ]);
    }

    public function form($id)
    {
        $user = $this->userRepository->getUserById($id);
        return Inertia::render('Users/Edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required']
        ]);

        $result = $this->userRepository->updateUser($request->id, $validatedData);
        if ($result === true) {
            return redirect()->to('/users')->with('success', 'Update Successful !');
        }
    }
}
