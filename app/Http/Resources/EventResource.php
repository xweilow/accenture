<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'deleted_at' => $this->deleted_at == null ? null : date('Y-m-d H:i:s', strtotime($this->deleted_at)),
        ];
    }
}
