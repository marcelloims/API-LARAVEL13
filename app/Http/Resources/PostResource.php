<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'post_id'       => $this->id,
            'user'          => new UserResource($this->whenLoaded('user')),
            'title'         => $this->title,
            'slug'          => $this->slug,
            'created_at'    => Carbon::parse($this->created_at)->format('d M Y H:i:s')
        ];
    }
}
