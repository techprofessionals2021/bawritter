<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $relativePath = str_replace(asset(''), '', $this->name);
        return [
            'id' => $this->id,
            'name' => asset($this->name),
            'display_name' => $this->display_name,
            'size_in_kb' => Storage::exists($relativePath) 
                ? round(Storage::size($relativePath) / 1024) 
                : null, // Handle missing files gracefully
        ];
    }
}
