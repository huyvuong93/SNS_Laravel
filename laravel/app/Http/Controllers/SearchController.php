<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use Inertia\Inertia;

class SearchController extends Controller
{
    protected UserRepository $userRepository;
    protected PostRepository $postRepository;

    public function __construct(UserRepository $userRepository, PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $keywords = $request->keywords;
        $searchType = $request->type;
        $users = $this->userRepository->getUserByKeywords($keywords);
        $posts = $this->postRepository->getPostBySearchKeywords($keywords);
        if ($searchType === 'post') {
            return Inertia::render('Search/Post', [
                'posts' => $posts,
                'keywords' => $keywords,
            ]);
        } elseif ($searchType === 'user') {
            return Inertia::render('Search/User', [
                'users' => $users,
                'keywords' => $keywords,
            ]);
        }
    }
}
