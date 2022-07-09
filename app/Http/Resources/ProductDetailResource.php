<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'sale' => $this->sale,
            'available_quantity' => $this->available_quantity,
            'manufacturing_date' => $this->manufacturing_date,
            'color' => $this->color,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
