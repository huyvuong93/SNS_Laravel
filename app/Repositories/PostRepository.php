<?php

namespace App\Repositories;

use App\Models\Enum\ItemsPerPage;
use App\Models\Post;
use App\Models\FollowingUser;
use App\Models\PostImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostRepository
{
    // Query

    public function getPostById(int $id)
    {
        return Post::where('id', $id)->with('user')->first();
    }

    public function getMyNewsFeed($user)
    {
        $followingUserIds = FollowingUser::where('user_id', $user->id)->pluck('following_id')->toArray();
        array_push($followingUserIds, $user->id);

        return $this->getPostByUserIds($followingUserIds)->paginate(2);
    }

    public function getPostByUserIds(array $ids)
    {
        $query = Post::query();
        return $query->whereIn('user_id', $ids)
            ->with('user')
            ->with('images')
            ->with('comments')
            ->withCount('likes')
            ->orderBy('created_at', 'desc');
    }


    public function getPostBySearchKeywords(?string $keywords)
    {
        $query = Post::query();
        if (isset($keywords)) {
            $query->where('content', 'like', $keywords);
        }
        return $query->with('user')->with('images')->paginate(1);
    }




    // Action

    public function handlePostBeforeUploading(?array $attributes, $user)
    {
        $images = [];
        if (!empty($attributes['images'])) {
            $images = $this->handleImages($attributes['images']);
        }
        $attributes['images'] = $images;
        $attributes['user_id'] = $user->id;
        return $attributes;
    }

    public function handleImages(array $inputImages)
    {
        $images = [];
        foreach ($inputImages as $image) {
            $imageUrl = Storage::disk('public')->putFile('/posts', $image);
            array_push($images, $imageUrl);
        }

        return $images;
    }

    public function uploadPost(array $attributes)
    {
        DB::beginTransaction();
        try {
            $post = Post::create([
                'user_id' => $attributes['user_id'],
                'content' => $attributes['content'],
                'status' => $attributes['status'],
            ]);
            $this->uploadPostImages($attributes['images'], $post->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function uploadPostImages(array $images, $postId)
    {
        $data = [];
        foreach ($images as $image) {
            $data[] = [
                'post_id' => $postId,
                'image_url' => $image,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        PostImage::insert($data);
        return true;
    }

    public function updatePost(Post $post, array $data)
    {
        try {
            $post->update([
                'content' => $data['content'],
                'status' => $data['status'],
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return false;
        }
    }
}
