<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'logo'=>asset($this->logo),
            'favicon'=>asset($this->favicon),
            'created'=>$this->created_at->format('Y-M-D'),
            'instagram'=>$this->instagram,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'title'=>$this->title,
            'content'=>$this->content,
            'address'=>$this->address,
        ];
    }
}
