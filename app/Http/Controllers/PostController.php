<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use App\Repositories\CommentRepository;
use Inertia\Inertia;

class PostController extends Controller
{
    protected UserRepository $userRepository;
    protected PostRepository $postRepository;
    protected CommentRepository $commentRepository;

    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        CommentRepository $commentRepository
    ) {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $updateData = $request->all();
        $post = $this->postRepository->getPostById($id);
        $result = $this->postRepository->updatePost($post, $updateData);
        if ($result === true) {
            return back()->with('success', 'Update Successfully');
        } else {
            return back()->withErrors(['Update Successfully']);
        }
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Request $request, int $id)
    {
        $user = $this->userRepository->getCurrentUser();
        $post = $this->postRepository->getPostById($id);
        $comments = $this->commentRepository->getCommentByPostId($id);

        if ($request->wantsJson()) {
            return response()->json($comments);
        }
        return Inertia::render('Post', [
            'user' => $user,
            'post' => $post,
            'comments' => $comments
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
