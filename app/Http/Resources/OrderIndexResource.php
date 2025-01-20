<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Optional;

class OrderIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_no' => $this->number,
            'title' => $this->title,
            'dead_line' => $this->dead_line ?? '',
            'status' => optional($this->status)->name,
            'service' => optional($this->service)->name,
            'deadline' => convertToLocalTime($this->dead_line),   
            'assign_to' => optional($this->assignee)->first_name,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
