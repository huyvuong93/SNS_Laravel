<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'send_mes_user_id',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'send_mes_user_id', 'id');
    }
}
