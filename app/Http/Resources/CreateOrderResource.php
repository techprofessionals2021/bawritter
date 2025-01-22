<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;



class CreateOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'writer_list' => collect($this['writer_list'] ?? [])->map(function($writer) {
                return [
                    'id' => $writer->id,
                    'full_name' => trim(($writer->first_name ?? '').' '.($writer->last_name ?? '')),
                    'double_space_price' => $writer->staff_price->double_space_price ?? 0,
                    'single_space_price' => $writer->staff_price->single_space_price ?? 0,
                ];
            })->all(),

            'service_id_list' => collect($this['service_id_list'] ?? [])->map(function($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                ];
            })->all(),

            'work_level_id_list' => collect($this['work_level_id_list'] ?? [])->map(function($level) {
                return [
                    'id' => $level->id,
                    'name' => $level->name,
                    'percentage_to_add' => round($level->percentage_to_add, 2),
                ];
            })->all(),

            'urgency_id_list' => collect($this['urgency_id_list'] ?? [])->map(function($urgency) {
                return [
                    'id' => $urgency['id'] ?? null,
                    'name' => $urgency['name'] ?? null,
                    'percentage_to_add' => $urgency['percentage_to_add'] ?? null,
                ];
            })->all(),
            'spacings_list' => collect($this['spacings_list'] ?? [])->map(function($spacing) {
                return [
                    'id' => $spacing['id'] ?? null,
                    'name' => $spacing['name'] ?? null,
                ];
            })->all(),
        ];
    }
}
