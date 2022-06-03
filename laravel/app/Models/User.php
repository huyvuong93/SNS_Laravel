<?php

namespace App\Models;

use App\Notifications\FriendReqNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city',
        'address',
        'job',
        'self_introduce',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class)
            ->with('likes')
            ->with('comments')
            ->with('images')
            ->with('user')
            ->withCount('likes');
    }

    public function followings()
    {
        return $this->hasMany(FollowingUser::class);
    }


    public function privateChatRooms()
    {
        return $this->hasMany(PrivateChat::class)->select('room_id');
    }

    public function friends()
    {
        return $this->hasMany(Friend::class);
    }

    public function messages()
    {
        return $this->hasMany(PrivateChatMessage::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function likedPostIds()
    {
        return $this->hasMany(PostLike::class)->select('post_id');
    }

    public function likedCommentIds()
    {
        return $this->hasMany(CommentLike::class)->select('comment_id');

    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }
}
