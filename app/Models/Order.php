<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\WorkLevel;
use App\Models\Urgency;
use Carbon\Carbon;
use App\Models\OrderStatus;
use App\Models\User;
use App\Traits\Wallet\Transactionable;

class Order extends Model
{
    use SoftDeletes;
    use \App\Traits\TagOperation;
    use Transactionable;


    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'dead_line' => 'datetime',
    ];

    protected $fillable = [
        'number',
        'title',
        'instruction',
        'customer_id',
        'service_id',
        'work_level_id',
        'urgency_id',
        'dead_line',
        'unit_name',
        'base_price',
        'work_level_price',
        'urgency_price',
        'unit_price',
        'quantity',
        'amount',
        'sub_total',
        'discount',
        'total',
        'staff_payment_amount',
        'spacing_type',
        'work_level_percentage',
        'urgency_percentage',
        'staff_id',
        'order_status_id',
        'update_via_sms',
        'billed',
        'staff_id_from_client',
    ];

    function walletPayment()
    {
        $transaction = $this->walletTransactions()->get();

        if ($transaction->count() > 0) {
            return $this->walletTransactions()->get()->first()->pivot;
        }
    }

    function status()
    {
        return $this->hasOne('App\Models\OrderStatus', 'id', 'order_status_id');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment');
    }

    function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'order_id', 'user_id');
    }

    function isAFollower($user_id)
    {
        return $this->followers()->where('user_id', $user_id)->exists();
    }

    function added_services()
    {
        return $this->hasMany('App\Models\OrderAdditionalService');
    }

    function service()
    {
        return $this->hasOne('App\Models\Service', 'id', 'service_id');
    }

    function work_level()
    {
        return $this->hasOne('App\Models\WorkLevel', 'id', 'work_level_id');
    }

    function urgency()
    {
        return $this->hasOne('App\Models\Urgency', 'id', 'urgency_id');
    }

    function assignee()
    {
        return $this->belongsTo('App\Models\User', 'staff_id', 'id');
    }
    function assignee_from_client()
    {
        return $this->belongsTo('App\Models\User', 'staff_id_from_client', 'id');
    }

    function customer()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }

    public function rating()
    {
        return $this->hasOne('App\Models\Rating');
    }

    function submitted_works()
    {
        return $this->hasMany('App\Models\SubmittedWork');
    }

    function latest_submitted_work()
    {
        $attachments = $this->submitted_works()
            ->orderBy('id', 'DESC')
            ->get();

        if ($attachments->count() > 0) {
            return $attachments->first();
        }

        return $attachments;
    }

    function revisionUsed()
    {
        return $this->submitted_works()->where('needs_revision', 1)->count();
    }

    static function admin_dropdown()
    {
        $data['staff_list'] = [
            '' => 'Select'
        ] + User::role([
            'staff'
        ])->orderBy('first_name', 'ASC')
            ->select(DB::raw('CONCAT(first_name, " ", last_name) AS name'), 'id')
            ->pluck('name', 'id')
            ->toArray();

        $data['order_status_list'] = OrderStatus::where('id', '<>', ORDER_STATUS_PENDING_PAYMENT)->orderBy('id', 'ASC')->pluck('name', 'id')->toArray();

        return $data;
    }

    static function task_dropdown()
    {
        $data['order_status_list'] = [
            '' => 'All'
        ] + OrderStatus::orderBy('id', 'ASC')
            ->whereIn('id', self::orderStatusAllowedForStaff())->pluck('name', 'id')->toArray();

        $data['dead_line_list'] = [
            '' => 'N/A',
            'today' => 'Today',
            'tommorrow' => 'Tommorrow',
            'day_after_tommorrow' => 'The day after tommorrow'
        ];

        return $data;
    }

    static function customer_dropdown()
    {
        $data['order_status_list'] = [
            '' => 'All'
        ] + OrderStatus::orderBy('id', 'ASC')->pluck('name', 'id')->toArray();

        $data['dead_line_list'] = [
            '' => 'N/A',
            'today' => 'Today',
            'tommorrow' => 'Tommorrow',
            'day_after_tommorrow' => 'The day after tommorrow'
        ];

        return $data;
    }

    static function dropdown()
    {

        $data['writer_list'] = User::role('staff')->whereHas('staff_price')->with('staff_price')->get();
        $data['service_id_list'] = Service::orderBy('name', 'ASC')->whereNull('inactive')->where('price_type_id', 3)->get();
        $data['work_level_id_list'] = WorkLevel::orderBy('id', 'ASC')->whereNull('inactive')->get();
        $urgencies = Urgency::whereNull('inactive')
            ->orderBy('percentage_to_add', 'ASC')->get();

        $urgency_list = [];

        if ($urgencies->count() > 0) {
            foreach ($urgencies as $urgency) {
                $str = $urgency->value . ' ' . $urgency->type . ' / ';

                $str .= get_urgency_date($urgency->type, $urgency->value);

                $date = get_urgency_date($urgency->type, $urgency->value, 'Y-m-d');

                $urgency_list[] = [
                    'id' => $urgency->id,
                    'name' => $str,
                    'value' => $urgency->value,
                    'percentage_to_add' => $urgency->percentage_to_add,
                    'date' => $date
                ];
            }
        }
        $data['urgency_id_list'] = $urgency_list;

        $data['spacings_list'] = [
            [
                'id' => 'double',
                'name' => "Double-spaced"
            ],
            [
                'id' => 'single',
                'name' => "Single-spaced"
            ]
        ];

        return $data;
    }

    static function orderStatusAllowedForStaff()
    {
        return [
            ORDER_STATUS_NEW,
            ORDER_STATUS_IN_PROGRESS,
            ORDER_STATUS_SUBMITTED_FOR_APPROVAL,
            ORDER_STATUS_REQUESTED_FOR_REVISION,
            ORDER_STATUS_COMPLETE,
            ORDER_STATUS_ON_HOLD
        ];
    }
    static function statistics($staff_id = NULL)
    {
        $orders = Order::select('order_status_id', DB::raw('count(*) as total'))
            ->whereNull('archived')->groupBy('order_status_id');
        if ($staff_id) {
            $orders->where('staff_id', $staff_id);
            $statuses = OrderStatus::whereIn('id', self::orderStatusAllowedForStaff())->get();
        } else {
            $statuses = OrderStatus::where('id', '<>', ORDER_STATUS_PENDING_PAYMENT)->get();
        }

        $orders = $orders->pluck('total', 'order_status_id');

        if ($statuses->count() > 0) {
            $statuses = $statuses->toArray();

            foreach ($statuses as $key => $status) {
                $statuses[$key]['value'] = (!isset($orders[$status['id']])) ? 0 : $orders[$status['id']];
            }

            $statuses = array_chunk($statuses, 10);
        }

        return $statuses;
    }

    // for API

    static function apiStatistics($staff_id = NULL)
    {
        $orders = Order::select('order_status_id', DB::raw('count(*) as total'))
            ->whereNull('archived')->groupBy('order_status_id');
        if ($staff_id) {
            $orders->where('staff_id', $staff_id);
            $statuses = OrderStatus::whereIn('id', self::orderStatusAllowedForStaff())->get();
        } else {
            $statuses = OrderStatus::where('id', '<>', ORDER_STATUS_PENDING_PAYMENT)->get();
        }

        $orders = $orders->pluck('total', 'order_status_id');

        if ($statuses->count() > 0) {
            $statuses = $statuses->toArray();

            foreach ($statuses as $key => $status) {
                $statuses[$key]['value'] = (!isset($orders[$status['id']])) ? 0 : $orders[$status['id']];
            }

            $statuses = array_chunk($statuses, 6);
        }

        return $statuses;
    }
}
