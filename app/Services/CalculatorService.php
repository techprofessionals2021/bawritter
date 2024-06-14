<?php

namespace App\Services;

use App\Models\Service;
use App\Models\WorkLevel;
use App\Models\Urgency;
use App\Models\AdditionalService;
use App\Models\Setting;
use App\Enums\PriceType;
use App\Enums\SpacingType;

class CalculatorService
{
    /*
        The calculatePrice method requires the following keys to contain in
        the request array parameter:
            $request['service_id']
            $request['work_level_id']
            $request['urgency_id']
            $request['spacing_type']
            $request['quantity']
            $request['added_services']
    */

    function calculatePrice($request)
    {
        // Retrieve service, work level, and urgency information
        $service = Service::find($request['service_id']);
        $workLevel = WorkLevel::find($request['work_level_id']);
        $urgency = Urgency::find($request['urgency_id']);
    
        // Initialize variables
        $base_price = 0;
        $unit_name = '';
    
        // Determine base price and unit name based on price type
        switch ($service->price_type_id) {
            case PriceType::Fixed:
                $base_price = $service->price;
                $unit_name = PriceType::FixedPriceUnit;
                break;
            case PriceType::PerWord:
                $base_price = $service->price;
                $unit_name = PriceType::PerWordPriceUnit;
                break;
            case PriceType::PerPage:
                $base_price = $request['base_price'];
                $unit_name = PriceType::PerPagePriceUnit;
                break;
            default:
                $request['spacing_type'] = null;
                break;
        }
    
        // Calculate work level and urgency prices
        $work_level_price = $this->calculatePercentage($base_price, $workLevel->percentage_to_add);
        $urgency_price = $this->calculatePercentage($base_price, $urgency->percentage_to_add);
    
        // Calculate unit price and amount
        $unit_price = $base_price + $work_level_price + $urgency_price;
        $amount = $this->roundPrice($unit_price * $request['quantity']);
    
        // Calculate additional services cost
        $additional_services_cost = $this->getTotalPriceoOfAdditionalServices($request['added_services'] ?? []);
    
        // Calculate sub-total and total
        $sub_total = $this->roundPrice($amount + $additional_services_cost);
        $total = $sub_total; // Add discount logic here if needed
    
        // Return calculated prices and details
        return [
            'spacing_type' => $request['spacing_type'],
            'unit_name' => $unit_name,
            'base_price' => $base_price,
            'work_level_price' => $work_level_price,
            'urgency_price' => $urgency_price,
            'unit_price' => $unit_price,
            'amount' => $amount,
            'additional_services_cost' => $additional_services_cost,
            'sub_total' => $sub_total,
            'discount' => 0, // Placeholder for discount logic
            'total' => $total,
        ];
    }
    
    function orderTotal($request)
    {
        return $this->calculatePrice($request)['total'];
    }

    private function calculatePercentage($basePrice, $percentageToAdd)
    {
        return $this->roundPrice((($basePrice * $percentageToAdd) / 100));
    }

    private function getTotalPriceoOfAdditionalServices($added_services)
    {
        if (isset($added_services) && is_array($added_services) && count($added_services) > 0) {
            foreach ($added_services as $row) {
                $service_ids[] = $row['id'];
            }

            return AdditionalService::whereIn('id', $service_ids)->sum('rate');
        }

        return 0;
    }

    public function priceList()
    {
        $record['work_levels'] = [];
        $record['pricings'] = [];
        $record['services'] = [];

        $services = Service::whereNull('inactive')->get();
        $workLevels = WorkLevel::whereNull('inactive')->orderBy('percentage_to_add', 'ASC')->get();
        $urgencies = Urgency::whereNull('inactive')->orderBy('percentage_to_add', 'ASC')->get();

        if ($services->count() > 0 && $workLevels->count() > 0 && $urgencies->count() > 0) {
            foreach ($services as $service) {
                $record['pricings'][$service->id] = $this->getPriceByService($service, $workLevels, $urgencies);
            }

            $record['work_levels'] = $workLevels->toArray();
            $record['services'] = $services->toArray();
        }

        return $record;
    }

    private function getPriceByService($service, $workLevels, $urgencies)
    {
        foreach ($urgencies as $urgency) {
            $data[] = [
                'name' => $urgency->value . ' ' . $urgency->type,
                'record' => $this->calculateServicePrice($workLevels, $urgency, $service)
            ];
        }

        return $data;
    }

    private function calculateServicePrice($workLevels, $urgency, $service)
    {
        foreach ($workLevels as $workLevel) {

            $price = $this->calculatePrice([
                'service_id' => $service->id,
                'work_level_id' => $workLevel->id,
                'urgency_id' => $urgency->id,
                'spacing_type' => $workLevel->id,
                'quantity' => $service->minimum_order_quantity,
                'added_services' => [],
            ]);
            $data[] = $price['total'];
        }

        return $data;
    }

    public function staffPaymentAmount($order_total)
    {
        if (Setting::get_setting('enable_browsing_work') == 'yes') {
            // Calculate Staff payment
            $payment_value = Setting::get_setting('staff_payment_amount');

            if (Setting::get_setting('staff_payment_type') == 'percentage') {
                return $this->roundPrice((($order_total * $payment_value) / 100));
            } else {
                return $payment_value;
            }
        }
        return NULL;
    }


    public function roundPrice($amount)
    {
        return number_format($amount, 2, '.', '');
    }
}
