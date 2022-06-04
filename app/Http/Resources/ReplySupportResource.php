<?php

namespace App\Http\Resources;

use App\Models\Support;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplySupportResource extends JsonResource
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
            'description' => $this->description,
            'dt_updated' => Carbon::make($this->updated_at)->format('Y-m-d H:i:s'),
            'support' => new SupportResource($this->whenLoaded('supports')),
            'user' => new UserResource($this->user)
        ];
    }
}
