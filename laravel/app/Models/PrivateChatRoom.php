<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
      'room_id'
    ];

    public function members()
    {
        return $this->hasMany(PrivateChat::class)->select('member_id');
    }
}
