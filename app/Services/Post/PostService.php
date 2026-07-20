<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Repositories\Post\PostRepository;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $_postRepository)
    {
        $this->postRepository = $_postRepository;
    }

    public function fetch($request)
    {
        return $this->postRepository->fetch($request);
    }

    public function getList()
    {
        return $this->postRepository->getData(Post::class, ['id','title','slug']);
    }

    public function detail($id)
    {
        return $this->postRepository->getDataById(Post::class, $id,['id','title','slug']);
    }

    public function store($request)
    {
        $data = array_merge(
            $request->validated(),
            ['slug' => Str::slug($request->title)],
            $this->postRepository->auditableCreate()
        );

        if ($this->postRepository->store(Post::class, $data)) {
            return response()->json([
                'success'   => true,
                'message'   => 'Data has been created'
            ],Response::HTTP_CREATED);
        }
    }

    public function update($request, $id)
    {
        $data = array_merge(
            $request->validated(),
            ['slug' => Str::slug($request->title)],
            $this->postRepository->auditableUpdate()
        );

        if ($this->postRepository->update(Post::class, $id, $data)) {
            return response()->json([
                'success'   => true,
                'message'   => 'Data has been updated'
            ],Response::HTTP_OK);
        }
    }

    public function destroy($id)
    {
        if ($this->postRepository->delete(Post::class, $id)) {
            return response()->json([
                'success'   => true,
                'message'   => 'Data has been deleted'
            ],Response::HTTP_OK);
        }
    }
}
