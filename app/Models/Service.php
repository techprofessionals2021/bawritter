<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\PriceType;
use App\models\AdditionalService;

class Service extends Model
{
    protected $fillable = [
        'id',
        'name',
        'price_type_id',
        'price',
        'single_spacing_price',
        'double_spacing_price',
        'minimum_order_quantity',
        'inactive'
    ];

    /**
     * The additionalServices that belong to the Service.
     */
    public function additionalServices()
    {
        return $this->belongsToMany('App\models\AdditionalService', 'service_tag_additional_services');
    }

    public function price_type()
    {
        return $this->belongsTo('App\models\PriceType');
    }

    public static function dropdown()
    {
        $data['price_type_list'] = PriceType::pluck('name', 'id')->toArray();
        $data['additional_services_list'] = AdditionalService::pluck('name', 'id')->toArray();
        return $data;
    }
}
