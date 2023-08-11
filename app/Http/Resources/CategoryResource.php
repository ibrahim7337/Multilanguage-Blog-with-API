<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'image'=>asset($this->image),
            'parent'=>$this->parent,
            'created_at'=>$this->created_at->format('Y-M-D'),
            'title'=>$this->title,
            'content'=>$this->content,
            'children'=>CategoryCollection::make($this->whenLoaded('children')),
            'posts'=>PostResource::collection($this->whenLoaded('posts')),
        ];
    }
}
