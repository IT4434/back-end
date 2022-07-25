<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'shipping_address' => $this->shipping_address,
            'total_price' => $this->total_price,
            'order_status' => $this->order_status,
            'order_detail' => OrderDetailResource::collection($this->whenLoaded('orderDetails')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
