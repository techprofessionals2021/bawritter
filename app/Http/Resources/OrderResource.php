<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? '',
            'order_no' => $this->number ?? '',
            'title' => $this->title ?? '',
            'customer' => optional($this->customer)->first_name ?? '',
            'dead_line' => $this->dead_line ?? '',
            'quantity' => $this->quantity ?? '',
            'base_price' => $this->base_price ?? '',
            'work_level_price' => $this->work_level_price ?? '',
            'urgency_price' => $this->urgency_price ?? '',
            'unit_price' => $this->unit_price ?? '',
            'amount' => $this->amount ?? '',
            'sub_total' => $this->sub_total ?? '',
            'discount' => $this->discount ?? '',
            'total' => $this->total ?? '',
            'assign_to' => optional($this->assignee)->first_name ?? '',
            'status' => optional($this->status)->name ?? '',
            'submitted_work' => AttachmentResource::collection($this->submitted_works),
            'order_attachments' => AttachmentResource::collection($this->attachments),
            'created_at' => optional($this->created_at)->toDateTimeString() ?? '',
            'updated_at' => optional($this->updated_at)->toDateTimeString() ?? '',
        ];

    }
}
