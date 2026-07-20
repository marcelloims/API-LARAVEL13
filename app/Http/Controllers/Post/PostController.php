<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Services\Post\PostService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/*
    "Please Read Me"
    pastikan di postman ada Request : keyword, perPage untuk function "fetch"
    {
        "perPage": 10,
        "keyword": ""
    }
*/

class PostController extends Controller
{
    use ApiResponse;

    protected $postService;

    public function __construct(PostService $_postService)
    {
        $this->postService = $_postService;
    }

    public function fetch(Request $request)
    {
        try {
            return PostResource::collection($this->postService->fetch($request));
        } catch (\Throwable $e) {
            return $this->errorResponse('errors', 500, $e->getMessage());
        }
    }

    public function getList()
    {
        try {
            return PostResource::collection($this->postService->getList());
        } catch (\Throwable $e) {
            return $this->errorResponse('errors', 500, $e->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            return new PostResource($this->postService->detail($id));
        } catch (\Throwable $e) {
            return $this->errorResponse('errors', 500, $e->getMessage());
        }
    }

    public function store(PostRequest $request)
    {
        return $this->postService->store($request);
    }

    public function update(PostRequest $request, $id)
    {
        return $this->postService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->postService->destroy($id);
    }
}
