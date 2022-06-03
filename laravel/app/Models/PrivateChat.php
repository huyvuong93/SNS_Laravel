<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'member_id',
        'user_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
