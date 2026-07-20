<?php

namespace App\Models\Traits\Post;

use App\Models\User;

trait PostRelationship
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
