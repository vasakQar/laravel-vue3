<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = Storage::url('public/'.$this->image);
        return [
            'id' => $this->id,
            'user' => $this->user,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $imageUrl,
            'created_at' => $this->created_at,
        ];
    }
}
