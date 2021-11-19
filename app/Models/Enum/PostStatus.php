<?php


namespace App\Models\Enum;


class PostStatus extends Enum
{
    const ALL = 'all';
    const ONLY_FRIEND = 'only_friend';
    const ONLY_ME = 'only_me';
}
