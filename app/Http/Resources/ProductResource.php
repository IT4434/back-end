<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'category_id' => $this->category_id,
            'brand' => $this->brand,
            'sale' => $this->sale,
            'description' => $this->description,
            'sold_quantity' => $this->sold_quantity,
            'rating' => $this->rating,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'details' => ProductDetailResource::collection($this->whenLoaded('productDetails')),
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
