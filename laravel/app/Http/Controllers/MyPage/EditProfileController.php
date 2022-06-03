<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class EditProfileController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
    }

    public function edit(EditUserRequest $request)
    {
        $data = $request->all();
        $user = $this->userRepository->getCurrentUser();
        $user->update($data);

        return redirect()->to('/mypage');
    }
}
