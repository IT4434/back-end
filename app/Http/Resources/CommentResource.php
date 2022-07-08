<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        switch ($this->commentable_type) {
            case 'App\Models\User':
                $type = 'User';
                $commenter = new UserResource($this->commentable);
            break;

            case 'App\Models\Admin':
                $type = 'Admin';
                $commenter = new UserResource($this->commentable);
            break;

            default:
                $type = '';
                $commenter = '';
            break;
        }
        return [
            'id' => $this->id,
            'body' => $this->body,
            'product' => new ProductResource($this->whenLoaded('product')),
            'commentable_id' => $this->commentable_id,
            'commentable_type' => $type,
            'commenter' => $commenter,
            'rating' => $this->rating,
        ];
    }
}
