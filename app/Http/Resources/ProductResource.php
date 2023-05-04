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
            'type'=> 'products',
            'attributes' => [
                'product_name'=>$this->product_name,
                'price'=>$this->price,
                // 'quantity'=>$this->sum('quantity')
                'quantity'=>$this->quantity,
                'expiry_date'=>$this->expiry_date,
            ]];
    }
}
