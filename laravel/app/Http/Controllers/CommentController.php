<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;
use Inertia\Inertia;

class CommentController extends Controller
{
    protected UserRepository $userRepository;
    protected CommentRepository $commentRepository;
    protected PostRepository $postRepository;


    public function __construct(
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        PostRepository $postRepository
    ) {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $this->userRepository->getCurrentUser();
        $validatedData = $request->validate([
            'content' => ['required', 'string', 'max:512']
        ]);
        $comment = $this->commentRepository->comment($user->id, $request->id, $validatedData['content']);
        return response()->json($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }

    public function more()
    {
        //
    }
}
