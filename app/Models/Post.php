<?php

namespace App\Models;

use App\Models\Traits\Post\PostRelationship;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use PostRelationship;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
