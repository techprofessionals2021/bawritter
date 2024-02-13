<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumberGenerator extends Model
{

    public $timestamps = false;

    static function gen($generatable_type)
    {
        $obj = self::where('generatable_type', $generatable_type)->get()->first();

        if ($obj) {
            $obj->last_generated_value++;
            $generated_number = sprintf('%06d', $obj->last_generated_value);
        } else {
            $obj = new NumberGenerator();
            $obj->generatable_type = $generatable_type;
            $generated_number = "000001";
        }

        $obj->last_generated_value = $generated_number;
        $obj->save();

        return self::get_prefix($generatable_type) . "-" . $generated_number;
    }

    private static function get_prefix($generatable_type)
    {
        $prefix_list = [
            'App\Models\Bill' => 'BILL',
            'App\Models\Order' => 'BAW',
            'App\Models\Payment' => 'PMT',
            'App\Models\Wallet' => 'WAL',
        ];

        return (isset($prefix_list[$generatable_type])) ? $prefix_list[$generatable_type] : NULL;
    }
}
