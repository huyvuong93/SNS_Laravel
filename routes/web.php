<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewsFeedController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MyPage\HomeController;
use App\Http\Controllers\MyPage\EditProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
require __DIR__.'/auth.php';

// Other User
Route::get('/users/{id}', [UserController::class, 'index'])->name('users.index');

// My Profile
Route::get('/mypage', [HomeController::class, 'index'])->name('mypage.index');
Route::post('/mypage/edit', [EditProfileController::class, 'edit'])->name('profile.edit');

// Post
Route::get('/', [NewsFeedController::class, 'index'])->name('newsfeed');
Route::post('/post/upload', [NewsFeedController::class, 'store'])->name('post.upload');
Route::post('/post/edit/{id}', [PostController::class, 'update'])->name('post.edit');
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');

// Search
Route::get('/search/{type}', [SearchController::class, 'index'])->name('search.index');

// Follow
Route::post('/users/follow', [FollowController::class, 'follow'])->name('users.follow');
Route::get('api/followings', [FollowController::class, 'getFollowingUsers'])->name('users.followings.api');

// Friend
Route::post('/users/friend_req_sent', [FriendRequestController::class, 'send'])->name('users.friend.request');
Route::get('/users/friend_req/accept/{sendId}/{receiveId}', [FriendRequestController::class, 'accept'])->name('users.friend.request.accept');
Route::post('/users/friend_req/ignore/{sendId}/{receiveId}', [FriendRequestController::class, 'ignore'])->name('users.friend.request.ignore');

// Comment
Route::post('/post/{id}/comment', [CommentController::class, 'store'])->name('post.comment');
Route::get('/post/{id}/more_comment', [CommentController::class, 'more'])->name('post.comment.more');

// Like
Route::post('like/{type}', [LikeController::class, 'like'])->name('like');

// Notification
Route::get('/notifications', [NotificationController::class, 'index'])->name('users.notification');

// PrivateChat
Route::get('/chat', [ChatController::class, 'index'])->name('users.chat.index');
Route::get('/users/private_chat/{roomId}', [ChatController::class, 'show'])->name('users.private_chat.show');
Route::post('/send_message', [ChatController::class, 'create'])->name('users.private_chat.send');
Route::get('/users/retrieve_messages/{id}', [ChatController::class, 'retrieveMessages'])->name('users.private_chat.retrieve');


