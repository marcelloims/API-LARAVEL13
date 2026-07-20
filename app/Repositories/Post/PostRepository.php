<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\BaseRepository;

class PostRepository extends BaseRepository
{
    public function fetch($request)
    {
        return Post::query()
            ->with(['user'])
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->keyword}%");
            })
            ->latest()
            ->select('id','user_id','title','slug','created_at')
            ->paginate($request->input('perPage', $request->perPage))
            ->withPath(url('/post/fetch'));
    }
}
